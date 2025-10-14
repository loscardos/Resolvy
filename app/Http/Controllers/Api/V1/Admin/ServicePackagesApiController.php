<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServicePackageRequest;
use App\Http\Requests\UpdateServicePackageRequest;
use App\Http\Resources\Admin\ServicePackageResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\ServicePackageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Service Packages",
 * description="API Endpoints for Service Packages"
 * )
 */
class ServicePackagesApiController extends Controller
{
    use ApiResponse;

    private $servicePackageRepository;

    public function __construct(ServicePackageRepositoryInterface $servicePackageRepository)
    {
        $this->servicePackageRepository = $servicePackageRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/service-packages",
     * operationId="getServicePackagesList",
     * tags={"Service Packages"},
     * summary="Get list of service packages",
     * description="Returns a paginated list of all service packages",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ServicePackageResource")),
     * @OA\Property(property="message", type="string", example="Service packages retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('service_package_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servicePackages = $this->servicePackageRepository->paginate(
            $request->query('per_page', 10)
        );

        return $this->successResponseWithPagination(
            $servicePackages,
            'Service packages retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/service-packages",
     * operationId="storeServicePackage",
     * tags={"Service Packages"},
     * summary="Create a new service package",
     * description="Creates a new service package record",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Service package data",
     * @OA\JsonContent(ref="#/components/schemas/StoreServicePackageRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Service package created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/ServicePackageResource"),
     * @OA\Property(property="message", type="string", example="Service package created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreServicePackageRequest $request)
    {
        $servicePackage = $this->servicePackageRepository->create($request->validated());

        return $this->successResponse(
            new ServicePackageResource($servicePackage),
            'Service package created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/service-packages/{id}",
     * operationId="getServicePackageById",
     * tags={"Service Packages"},
     * summary="Get service package information",
     * description="Returns service package data",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of service package to return",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/ServicePackageResource"),
     * @OA\Property(property="message", type="string", example="Service package retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('service_package_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servicePackage = $this->servicePackageRepository->find($id);

        if (!$servicePackage) {
            return $this->errorResponse('Service package not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new ServicePackageResource($servicePackage),
            'Service package retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/service-packages/{id}",
     * operationId="updateServicePackage",
     * tags={"Service Packages"},
     * summary="Update an existing service package",
     * description="Updates a service package record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of service package to update",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Service package data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateServicePackageRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Service package updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/ServicePackageResource"),
     * @OA\Property(property="message", type="string", example="Service package updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateServicePackageRequest $request, $id)
    {
        $this->servicePackageRepository->update($id, $request->validated());

        $servicePackage = $this->servicePackageRepository->find($id);

        return $this->successResponse(
            new ServicePackageResource($servicePackage),
            'Service package updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/service-packages/{id}",
     * operationId="deleteServicePackage",
     * tags={"Service Packages"},
     * summary="Delete an existing service package",
     * description="Deletes a service package record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of service package to delete",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Service package deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Service package deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('service_package_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->servicePackageRepository->delete($id);

        return $this->successResponse(
            null,
            'Service package deleted successfully.',
            Response::HTTP_OK
        );
    }
}
