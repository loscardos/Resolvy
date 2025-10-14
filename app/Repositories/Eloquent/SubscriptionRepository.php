<?php

namespace App\Repositories\Eloquent;

use App\Models\Subscription;
use App\Repositories\SubscriptionRepositoryInterface;
use Illuminate\Support\Collection;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{

    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritdoc
     */
    public function findByCustomerId(int $customerId): Collection
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->get();
    }
}
