<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketCategoryRequest;
use App\Http\Requests\UpdateTicketCategoryRequest;
use App\Http\Resources\Admin\TicketCategoryResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\TicketCategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Ticket Categories",
 * description="API Endpoints for Ticket Categories"
 * )
 */
class TicketCategoriesApiController extends Controller
{
    use ApiResponse;

    private $ticketCategoryRepository;

    public function __construct(TicketCategoryRepositoryInterface $ticketCategoryRepository)
    {
        $this->ticketCategoryRepository = $ticketCategoryRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/ticket-categories",
     * operationId="getTicketCategoriesList",
     * tags={"Ticket Categories"},
     * summary="Get list of ticket categories",
     * description="Returns a paginated list of all ticket categories",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TicketCategoryResource")),
     * @OA\Property(property="message", type="string", example="Ticket categories retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketCategories = $this->ticketCategoryRepository->paginate(
            $request->query('per_page', 10)
        );

        return $this->successResponseWithPagination(
            $ticketCategories,
            'Ticket categories retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/ticket-categories",
     * operationId="storeTicketCategory",
     * tags={"Ticket Categories"},
     * summary="Create a new ticket category",
     * description="Creates a new ticket category record",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Ticket category data",
     * @OA\JsonContent(ref="#/components/schemas/StoreTicketCategoryRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Ticket category created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/TicketCategoryResource"),
     * @OA\Property(property="message", type="string", example="Ticket category created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreTicketCategoryRequest $request)
    {
        $ticketCategory = $this->ticketCategoryRepository->create($request->validated());

        return $this->successResponse(
            new TicketCategoryResource($ticketCategory),
            'Ticket category created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/ticket-categories/{id}",
     * operationId="getTicketCategoryById",
     * tags={"Ticket Categories"},
     * summary="Get ticket category information",
     * description="Returns ticket category data",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of ticket category to return",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/TicketCategoryResource"),
     * @OA\Property(property="message", type="string", example="Ticket category retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('ticket_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketCategory = $this->ticketCategoryRepository->find($id);

        if (!$ticketCategory) {
            return $this->errorResponse('Ticket category not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new TicketCategoryResource($ticketCategory),
            'Ticket category retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/ticket-categories/{id}",
     * operationId="updateTicketCategory",
     * tags={"Ticket Categories"},
     * summary="Update an existing ticket category",
     * description="Updates a ticket category record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of ticket category to update",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Ticket category data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateTicketCategoryRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Ticket category updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/TicketCategoryResource"),
     * @OA\Property(property="message", type="string", example="Ticket category updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateTicketCategoryRequest $request, $id)
    {
        $this->ticketCategoryRepository->update($id, $request->validated());

        $ticketCategory = $this->ticketCategoryRepository->find($id);

        return $this->successResponse(
            new TicketCategoryResource($ticketCategory),
            'Ticket category updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/ticket-categories/{id}",
     * operationId="deleteTicketCategory",
     * tags={"Ticket Categories"},
     * summary="Delete an existing ticket category",
     * description="Deletes a ticket category record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of ticket category to delete",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Ticket category deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Ticket category deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('ticket_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->ticketCategoryRepository->delete($id);

        return $this->successResponse(
            null,
            'Ticket category deleted successfully.',
            Response::HTTP_OK
        );
    }
}
