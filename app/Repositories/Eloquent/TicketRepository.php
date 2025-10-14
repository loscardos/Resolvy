<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Models\Ticket;
use App\Repositories\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{

    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function create(array $payload): Ticket
    {
        return DB::transaction(function () use ($payload) {
            $customer = Customer::find($payload['customer_id']);
            $subscription = $customer ? $customer->subscriptions()->first() : null;

            $ticketData = [
                'ticket_no'       => 'TEMP',
                'subject'         => $payload['subject'],
                'description'     => $payload['description'],
                'customer_id'     => $payload['customer_id'],
                'subscription_id' => $subscription ? $subscription->id : null,
                'priority'        => $payload['priority'] ?? 'low',
                'ticket_category_id' => $payload['ticket_category_id'],
                'status'          => 'open',
            ];

            $ticket = $this->model->create($ticketData);

            if (!empty($payload['assigned_tos'])) {
                $ticket->assigned_tos()->sync($payload['assigned_tos']);
            }

            $nextId = $ticket->id;
            $paddedId = str_pad($nextId, 10, '0', STR_PAD_LEFT);
            $finalCode = 'SUP-' . $paddedId;

            $ticket->ticket_no = $finalCode;
            $ticket->save();

            $ticket->ticket_status_histories()->create([
                'from_status'    => '',
                'to_status'      => 'open',
                'updated_by_id'  => auth()->id(),
                'note'           => 'created',
            ]);

            return $ticket;
        });
    }


    /**
     * @inheritdoc
     */
    public function update(int $modelId, array $payload): bool
    {
        return DB::transaction(function () use ($modelId, $payload) {
            $ticket = Ticket::find($modelId);

            $isUpdated = $ticket->update([
                'subject'         => $payload['subject'],
                'description'     => $payload['description'],
                'priority'        => $payload['priority'] ?? 'low',
                'ticket_category_id' => $payload['ticket_category_id'],
            ]);

            if (!empty($payload['assigned_tos'])) {
                $ticket->assigned_tos()->sync($payload['assigned_tos']);
            }

            $ticket->ticket_status_histories()->create([
                'from_status'    => 'open',
                'to_status'      => 'open',
                'updated_by_id'  => auth()->id(),
                'note'           => 'updated',
            ]);

            return $isUpdated;
        });
    }

    /**
     * @inheritdoc
     */
    public function updateStatus(int $modelId, string $toStatus): bool
    {
        return DB::transaction(function () use ($modelId, $toStatus) {
            $ticket = $this->find($modelId);

            if (!$ticket) {
                return false;
            }

            $fromStatus = $ticket->status;

            if ($toStatus === 'closed' && $fromStatus !== 'resolved') {
                return false;
            }

            if (in_array($toStatus, ['closed', 'cancelled'])) {
                if (Gate::denies('ticket_update_status_close')) {
                    return false;
                }
            }

            $isUpdated = $ticket->update([
                'status' => $toStatus
            ]);

            if ($isUpdated) {
                $ticket->ticket_status_histories()->create([
                    'from_status'    => $fromStatus,
                    'to_status'      => $toStatus,
                    'updated_by_id'  => auth()->id(),
                    'note'           => 'updated',
                ]);
            }

            return $isUpdated;
        });
    }

    /**
     * @inheritdoc
     */
    public function assignUsers(int $ticketId, array $userIds): bool
    {
        $ticket = $this->find($ticketId);

        if (!$ticket) {
            return false;
        }

        $ticket->assigned_tos()->sync($userIds);

        return true;
    }
}
