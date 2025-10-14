<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Roles",
 * description="API Endpoints for Roles"
 * )
 */
class RolesApiController extends Controller
{
    use ApiResponse;

    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/roles",
     * operationId="getRolesList",
     * tags={"Roles"},
     * summary="Get list of roles",
     * description="Returns a paginated list of all roles with their permissions",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/RoleResource")),
     * @OA\Property(property="message", type="string", example="Roles retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = $this->roleRepository->paginate(
            $request->query('per_page', 10),
            ['*'],
            ['permissions']
        );

        return $this->successResponseWithPagination(
            $roles,
            'Roles retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/roles",
     * operationId="storeRole",
     * tags={"Roles"},
     * summary="Create a new role",
     * description="Creates a new role and assigns permissions",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Role data",
     * @OA\JsonContent(ref="#/components/schemas/StoreRoleRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Role created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/RoleResource"),
     * @OA\Property(property="message", type="string", example="Role created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleRepository->create($request->only('title'));
        $role->permissions()->sync($request->input('permissions', []));

        return $this->successResponse(
            new RoleResource($role->load(['permissions'])),
            'Role created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/roles/{id}",
     * operationId="getRoleById",
     * tags={"Roles"},
     * summary="Get role information",
     * description="Returns role data with assigned permissions",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of role to return",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/RoleResource"),
     * @OA\Property(property="message", type="string", example="Role retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role = $this->roleRepository->find($id, ['*'], ['permissions']);

        if (!$role) {
            return $this->errorResponse('Role not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new RoleResource($role),
            'Role retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/roles/{id}",
     * operationId="updateRole",
     * tags={"Roles"},
     * summary="Update an existing role",
     * description="Updates a role record and its permissions",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of role to update",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Role data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateRoleRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="Role updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/RoleResource"),
     * @OA\Property(property="message", type="string", example="Role updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return $this->errorResponse('Role not found.', Response::HTTP_NOT_FOUND);
        }

        $this->roleRepository->update($id, $request->only('title'));
        $role->permissions()->sync($request->input('permissions', []));

        $updatedRole = $this->roleRepository->find($id, ['*'], ['permissions']);

        return $this->successResponse(
            new RoleResource($updatedRole),
            'Role updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/roles/{id}",
     * operationId="deleteRole",
     * tags={"Roles"},
     * summary="Delete an existing role",
     * description="Deletes a role record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID of role to delete",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Role deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="Role deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->roleRepository->delete($id);

        return $this->successResponse(
            null,
            'Role deleted successfully.',
            Response::HTTP_OK
        );
    }
}
