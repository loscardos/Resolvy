<?php


namespace App\Repositories;


/**
 * This is the interface for the Customer repository.
 * It extends the base EloquentRepositoryInterface, inheriting its methods.
 */
interface TicketRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Updates the status of a ticket with business rule validation.
     *
     * @param int $modelId The ID of the ticket.
     * @param string $toStatus The target status.
     * @return bool True on success, false on failure or rule violation.
     */
    public function updateStatus(int $modelId, string $toStatus): bool;

    /**
     * Assign or re-assign a set of users to a specific ticket.
     *
     * @param int $ticketId The ID of the ticket.
     * @param array $userIds An array of user IDs to assign.
     * @return bool True on success, false if the ticket is not found.
     */
    public function assignUsers(int $ticketId, array $userIds): bool;
}
