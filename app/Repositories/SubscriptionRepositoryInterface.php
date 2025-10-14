<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

/**
 * This is the interface for the Customer repository.
 * It extends the base EloquentRepositoryInterface, inheriting its methods.
 */
interface SubscriptionRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Find all subscriptions belonging to a specific customer.
     *
     * @param int $customerId
     * @return Collection
     */
    public function findByCustomerId(int $customerId): Collection;
}
