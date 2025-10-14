<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketAssignmentsRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Resources\Admin\TicketResource;
use App\Http\Resources\Admin\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\Ticket;
use App\Repositories\TicketRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Tickets",
 * description="API Endpoints for Tickets"
 * )
 */
class TicketsApiController extends Controller
{
    use ApiResponse;

    private $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/tickets",
     * operationId="getTicketsList",
     * tags={"Tickets"},
     * summary="Get list of tickets",
     * description="Returns a paginated list of tickets",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TicketResource")),
     * @OA\Property(property="message", type="string", example="Tickets retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = $this->ticketRepository->paginate(
            $request->query('per_page', 10)
        );

        return $this->successResponseWithPagination(
            $tickets,
            'Tickets retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/tickets",
     * operationId="storeTicket",
     * tags={"Tickets"},
     * summary="Create a new ticket",
     * description="Creates a new ticket record",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Ticket data",
     * @OA\JsonContent(ref="#/components/schemas/StoreTicketRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Ticket created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/TicketResource"),
     * @OA\Property(property="message", type="string", example="Ticket created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketRepository->create($request->validated());

        return $this->successResponse(
            new TicketResource($ticket->load(['customer', 'subscription', 'ticket_category', 'assigned_tos'])),
            'Ticket created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/tickets/{id}",
     * operationId="getTicketById",
     * tags={"Tickets"},
     * summary="Get ticket information",
     * description="Returns ticket data",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of ticket to return", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/TicketResource"),
     * @OA\Property(property="message", type="string", example="Ticket retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket = $this->ticketRepository->find($id, ['*'], ['customer', 'subscription', 'ticket_category', 'assigned_tos', 'ticket_status_histories']);

        if (!$ticket) {
            return $this->errorResponse('Ticket not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new TicketResource($ticket),
            'Ticket retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/tickets/{id}",
     * operationId="updateTicket",
     * tags={"Tickets"},
     * summary="Update an existing ticket",
     * description="Updates a ticket record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of ticket to update", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * description="Ticket data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateTicketRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Ticket updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/TicketResource"),
     * @OA\Property(property="message", type="string", example="Ticket updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateTicketRequest $request, $id)
    {
        $this->ticketRepository->update($id, $request->validated());

        $ticket = $this->ticketRepository->find($id, ['*'], ['customer', 'subscription', 'ticket_category', 'assigned_tos', 'ticket_status_histories']);

        return $this->successResponse(
            new TicketResource($ticket),
            'Ticket updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/tickets/{id}",
     * operationId="deleteTicket",
     * tags={"Tickets"},
     * summary="Delete an existing ticket",
     * description="Deletes a ticket record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of ticket to delete", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Ticket deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Ticket deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->ticketRepository->delete($id);

        return $this->successResponse(
            null,
            'Ticket deleted successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/tickets/{id}/status",
     * operationId="updateTicketStatus",
     * tags={"Tickets"},
     * summary="Update a ticket's status",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateTicketStatusRequest")
     * ),
     * @OA\Response(response=200, description="Status updated successfully"),
     * @OA\Response(response=422, description="Status update failed due to validation or workflow rule violation")
     * )
     */
    public function updateStatus(UpdateTicketStatusRequest $request, $id)
    {
        $success = $this->ticketRepository->updateStatus($id, $request->status);

        if ($success) {
            return $this->successResponse(null, 'Status updated successfully.');
        }

        return $this->errorResponse(
            'Status update failed. The ticket may need to be resolved first before it can be closed.',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/tickets/{id}/assignments",
     * operationId="updateTicketAssignments",
     * tags={"Tickets"},
     * summary="Update a ticket's assigned users",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/UpdateTicketAssignmentsRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Assignments updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
     * @OA\Property(property="message", type="string", example="Assignments updated successfully.")
     * )
     * )
     * )
     */
    public function updateAssignments(UpdateTicketAssignmentsRequest $request, $id)
    {
        $this->ticketRepository->assignUsers($id, $request->input('assigned_tos', []));

        $ticket = $this->ticketRepository->find($id, ['*'], ['assigned_tos']);

        return $this->successResponse(
            UserResource::collection($ticket->assigned_tos),
            'Assignments updated successfully.'
        );
    }
}
