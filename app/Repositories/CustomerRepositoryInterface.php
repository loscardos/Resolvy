<?php

namespace App\Repositories;


use App\Models\Customer;

/**
 * This is the interface for the Customer repository.
 * It extends the base EloquentRepositoryInterface, inheriting its methods.
 */
interface CustomerRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Create a new Customer and their initial Subscription in a transaction.
     *
     * @param array $payload The combined data for customer and subscription.
     * @return Customer The newly created customer model.
     */
    public function createWithSubscription(array $payload): Customer;

    /**
     * Update a Customer and their associated Subscription in a transaction.
     *
     * @param int $customerId The ID of the customer to update.
     * @param array $payload The combined data for the customer and subscription.
     * @return Customer The updated customer model.
     */
    public function updateWithSubscription(int $customerId, array $payload): Customer;

    /**
     * Show a Customer and their associated Subscription in a transaction.
     *
     * @param string $customerCode The customer code of the customer.
     * @return Customer|null The updated customer model.
     */
    public function findByCode(string $customerCode): ?Customer;

}
