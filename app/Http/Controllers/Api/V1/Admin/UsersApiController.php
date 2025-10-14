<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Http\Traits\ApiResponse;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Users",
 * description="API Endpoints for User Management"
 * )
 */
class UsersApiController extends Controller
{
    use ApiResponse;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     * path="/api/v1/users",
     * operationId="getUsersList",
     * tags={"Users"},
     * summary="Get list of users",
     * description="Returns a paginated list of users with their roles",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="page", in="query", description="The page number", required=false, @OA\Schema(type="integer")),
     * @OA\Parameter(name="per_page", in="query", description="Number of items per page", required=false, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResourceWithRoles")),
     * @OA\Property(property="message", type="string", example="Users retrieved successfully."),
     * @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"),
     * @OA\Property(property="links", ref="#/components/schemas/PaginationLinks")
     * )
     * ),
     * @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = $this->userRepository->paginate(
            $request->query('per_page', 10),
            ['*'],
            ['roles']
        );

        return $this->successResponseWithPagination(
            $users,
            'Users retrieved successfully.'
        );
    }

    /**
     * @OA\Post(
     * path="/api/v1/users",
     * operationId="storeUser",
     * tags={"Users"},
     * summary="Create a new user",
     * description="Creates a new user and assigns roles",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="User data",
     * @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/UserResourceWithRoles"),
     * @OA\Property(property="message", type="string", example="User created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->create($request->validated());
        $user->roles()->sync($request->input('roles', []));

        return $this->successResponse(
            new UserResource($user->load(['roles'])),
            'User created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     * path="/api/v1/users/{id}",
     * operationId="getUserById",
     * tags={"Users"},
     * summary="Get user information",
     * description="Returns user data with assigned roles",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of user to return", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/UserResourceWithRoles"),
     * @OA\Property(property="message", type="string", example="User retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="Resource not found")
     * )
     */
    public function show($id)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = $this->userRepository->find($id, ['*'], ['roles']);

        if (!$user) {
            return $this->errorResponse('User not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(
            new UserResource($user),
            'User retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     * path="/api/v1/users/{id}",
     * operationId="updateUser",
     * tags={"Users"},
     * summary="Update an existing user",
     * description="Updates a user record and their roles",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of user to update", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * required=true,
     * description="User data",
     * @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     * ),
     * @OA\Response(
     * response=202,
     * description="User updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", ref="#/components/schemas/UserResourceWithRoles"),
     * @OA\Property(property="message", type="string", example="User updated successfully.")
     * )
     * )
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->errorResponse('User not found.', Response::HTTP_NOT_FOUND);
        }

        $this->userRepository->update($id, $request->except(['password', 'roles']));

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }
        $user->roles()->sync($request->input('roles', []));

        $updatedUser = $this->userRepository->find($id, ['*'], ['roles']);

        return $this->successResponse(
            new UserResource($updatedUser),
            'User updated successfully.',
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * @OA\Delete(
     * path="/api/v1/users/{id}",
     * operationId="deleteUser",
     * tags={"Users"},
     * summary="Delete an existing user",
     * description="Deletes a user record",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", description="ID of user to delete", required=true, @OA\Schema(type="integer")),
     * @OA\Response(
     * response=200,
     * description="User deleted successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", example=null),
     * @OA\Property(property="message", type="string", example="User deleted successfully.")
     * )
     * )
     * )
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->userRepository->delete($id);

        return $this->successResponse(
            null,
            'User deleted successfully.',
            Response::HTTP_OK
        );
    }
}
