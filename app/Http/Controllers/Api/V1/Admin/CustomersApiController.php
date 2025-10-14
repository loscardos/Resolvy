<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Admin\CustomerResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Customers",
 * description="API Endpoints for Customers"
 * )
 */
class CustomersApiController extends Controller
{
    use ApiResponse;

    private $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/customers",
     * operationId="getCustomersList",
     * tags={"Customers"},
     * summary="Get list of customers",
     * description="Returns a paginated list of customers",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CustomerResource")),
     * @OA\Property(property="message", type="string", example="Customers retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = $this->customerRepository->paginate(
            $request->query('per_page', 15)
        );

        return $this->successResponseWithPagination(
            $customers,
            'Customers retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/customers",
     * operationId="storeCustomer",
     * tags={"Customers"},
     * summary="Create a new customer and their initial subscription",
     * description="Creates a new customer record along with their first subscription",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Customer and subscription data",
     * @OA\JsonContent(ref="#/components/schemas/StoreCustomerRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Customer created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/CustomerResource"),
     * @OA\Property(property="message", type="string", example="Customer created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->customerRepository->createWithSubscription($request->validated());

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/customers/{id}",
     * operationId="getCustomerById",
     * tags={"Customers"},
     * summary="Get customer information by ID",
     * description="Returns customer data",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of customer to return", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/CustomerResource"),
     * @OA\Property(property="message", type="string", example="Customer retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            return $this->errorResponse('Customer not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer retrieved successfully.'
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/customers/by-code/{customer_code}",
     * operationId="getCustomerByCode",
     * tags={"Customers"},
     * summary="Get customer information by Customer Code",
     * description="Returns a single customer's data based on their unique customer code",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="customer_code",
     * in="path",
     * description="The unique code of the customer to return",
     * required=true,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/CustomerResource"),
     * @OA\Property(property="message", type="string", example="Customer retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Customer not found")
     * )
     */
    public function showByCode($customerCode)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer = $this->customerRepository->findByCode($customerCode);

        if (!$customer) {
            return $this->errorResponse('Customer not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/customers/{id}",
     * operationId="updateCustomer",
     * tags={"Customers"},
     * summary="Update an existing customer and their subscription",
     * description="Updates a customer record and their subscription",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of customer to update", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * description="Customer and subscription data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateCustomerRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Customer updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/CustomerResource"),
     * @OA\Property(property="message", type="string", example="Customer updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = $this->customerRepository->updateWithSubscription($id, $request->validated());

        return $this->successResponse(
            new CustomerResource($customer),
            'Customer updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/customers/{id}",
     * operationId="deleteCustomer",
     * tags={"Customers"},
     * summary="Delete an existing customer",
     * description="Deletes a customer record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of customer to delete", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Customer deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Customer deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->customerRepository->delete($id);

        return $this->successResponse(
            null,
            'Customer deleted successfully.',
            Response::HTTP_OK
        );
    }
}
