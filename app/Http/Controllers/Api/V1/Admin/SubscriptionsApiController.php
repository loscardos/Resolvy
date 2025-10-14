<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\Admin\SubscriptionResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\SubscriptionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Subscriptions",
 * description="API Endpoints for Subscriptions"
 * )
 */
class SubscriptionsApiController extends Controller
{
    use ApiResponse;

    private $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/subscriptions",
     * operationId="getSubscriptionsList",
     * tags={"Subscriptions"},
     * summary="Get list of subscriptions",
     * description="Returns a paginated list of subscriptions",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SubscriptionResource")),
     * @OA\Property(property="message", type="string", example="Subscriptions retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('subscription_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscriptions = $this->subscriptionRepository->paginate(
            $request->query('per_page', 10),
            ['*'],
            ['customer', 'package']
        );

        return $this->successResponseWithPagination(
            $subscriptions,
            'Subscriptions retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/subscriptions",
     * operationId="storeSubscription",
     * tags={"Subscriptions"},
     * summary="Create a new subscription",
     * description="Creates a new subscription record",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Subscription data",
     * @OA\JsonContent(ref="#/components/schemas/StoreSubscriptionRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Subscription created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/SubscriptionResource"),
     * @OA\Property(property="message", type="string", example="Subscription created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $subscription = $this->subscriptionRepository->create($request->validated());

        return $this->successResponse(
            new SubscriptionResource($subscription->load(['customer', 'package'])),
            'Subscription created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/subscriptions/{id}",
     * operationId="getSubscriptionById",
     * tags={"Subscriptions"},
     * summary="Get subscription information",
     * description="Returns subscription data",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of subscription to return", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/SubscriptionResource"),
     * @OA\Property(property="message", type="string", example="Subscription retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('subscription_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscription = $this->subscriptionRepository->find($id, ['*'], ['customer', 'package']);

        if (!$subscription) {
            return $this->errorResponse('Subscription not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new SubscriptionResource($subscription),
            'Subscription retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/subscriptions/{id}",
     * operationId="updateSubscription",
     * tags={"Subscriptions"},
     * summary="Update an existing subscription",
     * description="Updates a subscription record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of subscription to update", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * description="Subscription data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateSubscriptionRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Subscription updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/SubscriptionResource"),
     * @OA\Property(property="message", type="string", example="Subscription updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateSubscriptionRequest $request, $id)
    {
        $this->subscriptionRepository->update($id, $request->validated());

        $subscription = $this->subscriptionRepository->find($id, ['*'], ['customer', 'package']);

        return $this->successResponse(
            new SubscriptionResource($subscription),
            'Subscription updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/subscriptions/{id}",
     * operationId="deleteSubscription",
     * tags={"Subscriptions"},
     * summary="Delete an existing subscription",
     * description="Deletes a subscription record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of subscription to delete", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Subscription deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Subscription deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('subscription_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->subscriptionRepository->delete($id);

        return $this->successResponse(
            null,
            'Subscription deleted successfully.',
            Response::HTTP_OK
        );
    }
}
