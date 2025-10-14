<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'tickets';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const PRIORITY_SELECT = [
        'low'      => 'Low',
        'medium'   => 'Medium',
        'high'     => 'High',
        'critical' => 'Critical',
    ];

    public const STATUS_SELECT = [
        'open'        => 'Open',
        'in_progress' => 'In Progress',
        'on_hold'     => 'On Hold',
        'resolved'    => 'Resolved',
        'closed'      => 'Closed',
        'cancelled'   => 'Cancelled',
    ];

    protected $fillable = [
        'ticket_no',
        'subject',
        'description',
        'customer_id',
        'subscription_id',
        'ticket_category_id',
        'status',
        'priority',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function ticket_category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function assigned_tos()
    {
        return $this->belongsToMany(User::class);
    }

    public function ticket_status_histories(): HasMany
    {
        return $this->hasMany(TicketStatusHistory::class, 'ticket_id', 'id');
    }
}
