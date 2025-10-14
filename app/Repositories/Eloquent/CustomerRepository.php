<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{

    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function createWithSubscription(array $payload): Customer
    {
        return DB::transaction(function () use ($payload) {
            $customer = $this->model->create([
                'name' => $payload['name'],
                'customer_code' => 'TEMP',
                'type' => $payload['type'],
                'contact_email' => $payload['contact_email'],
                'contact_phone' => $payload['contact_phone'],
                'status' => $payload['status'],
            ]);

            $nextId = $customer->id;
            $paddedId = str_pad($nextId, 10, '0', STR_PAD_LEFT);
            $finalCode = 'CUS-' . $paddedId;

            $customer->customer_code = $finalCode;
            $customer->save();

            $customer->subscriptions()->create([
                'package_id' => $payload['package_id'],
                'start_date' => $payload['start_date'],
                'end_date' => $payload['end_date'],
                'notes' => $payload['notes'],
                'status' => $payload['subscription_status'],
            ]);

            return $customer;
        });
    }

    /**
     * @inheritdoc
     */
    public function updateWithSubscription(int $customerId, array $payload): Customer
    {
        return DB::transaction(function () use ($customerId, $payload) {
            $customer = $this->find($customerId);

            $customer->update([
                'name' => $payload['name'],
                'type' => $payload['type'],
                'contact_email' => $payload['contact_email'],
                'contact_phone' => $payload['contact_phone'],
                'status' => $payload['status'],
            ]);

            if ($subscription = $customer->subscriptions()->first()) {
                $subscription->update([
                    'package_id' => $payload['package_id'],
                    'start_date' => $payload['start_date'],
                    'end_date' => $payload['end_date'],
                    'notes' => $payload['notes'],
                    'status' => $payload['subscription_status'],
                ]);
            }

            return $customer->fresh();
        });
    }

    /**
     * @inheritdoc
     */
    public function findByCode(string $customerCode): ?Customer
    {
        return $this->model
            ->where('customer_code', $customerCode)
            ->with(['subscriptions', 'subscriptions.package'])
            ->first();
    }
}
