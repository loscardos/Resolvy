<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'service_packages';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const IS_ACTIVE_SELECT = [
        'active'   => 'Active',
        'Inactive' => 'Inactive',
    ];

    protected $fillable = [
        'name',
        'bandwidth_mbps_down',
        'bandwidth_mbps_up',
        'price',
        'description',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
