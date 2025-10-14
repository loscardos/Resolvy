<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\PermissionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Permissions",
 * description="API Endpoints for Permissions"
 * )
 */
class PermissionsApiController extends Controller
{
    use ApiResponse;

    private $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/permissions",
     * operationId="getPermissionsList",
     * tags={"Permissions"},
     * summary="Get list of permissions",
     * description="Returns a paginated list of all permissions",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PermissionResource")),
     * @OA\Property(property="message", type="string", example="Permissions retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = $this->permissionRepository->paginate(
            $request->query('per_page', 10)
        );

        return $this->successResponseWithPagination(
            $permissions,
            'Permissions retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/permissions",
     * operationId="storePermission",
     * tags={"Permissions"},
     * summary="Create a new permission",
     * description="Creates a new permission record",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Permission data",
     * @OA\JsonContent(ref="#/components/schemas/StorePermissionRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Permission created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/PermissionResource"),
     * @OA\Property(property="message", type="string", example="Permission created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionRepository->create($request->validated());

        return $this->successResponse(
            new PermissionResource($permission),
            'Permission created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/permissions/{id}",
     * operationId="getPermissionById",
     * tags={"Permissions"},
     * summary="Get permission information",
     * description="Returns permission data",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of permission to return",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/PermissionResource"),
     * @OA\Property(property="message", type="string", example="Permission retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission = $this->permissionRepository->find($id);

        if (!$permission) {
            return $this->errorResponse('Permission not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new PermissionResource($permission),
            'Permission retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/permissions/{id}",
     * operationId="updatePermission",
     * tags={"Permissions"},
     * summary="Update an existing permission",
     * description="Updates a permission record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of permission to update",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Permission data",
     * @OA\JsonContent(ref="#/components/schemas/UpdatePermissionRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Permission updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/PermissionResource"),
     * @OA\Property(property="message", type="string", example="Permission updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $this->permissionRepository->update($id, $request->validated());

        $permission = $this->permissionRepository->find($id);

        return $this->successResponse(
            new PermissionResource($permission),
            'Permission updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/permissions/{id}",
     * operationId="deletePermission",
     * tags={"Permissions"},
     * summary="Delete an existing permission",
     * description="Deletes a permission record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of permission to delete",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Permission deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Permission deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->permissionRepository->delete($id);

        return $this->successResponse(
            null,
            'Permission deleted successfully.',
            Response::HTTP_OK
        );
    }
}
