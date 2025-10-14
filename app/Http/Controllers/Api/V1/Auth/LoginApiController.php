<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponse; // <-- 1. Import the trait
use Symfony\Component\HttpFoundation\Response;

class LoginApiController extends Controller
{
    use ApiResponse; // <-- 2. Use the trait

    /**
     * @OA\Post(
     * path="/api/v1/login",
     * summary="Authenticate user and get API token",
     * tags={"Authentication"},
     * @OA\RequestBody(
     * required=true,
     * description="User credentials",
     * @OA\MediaType(
     * mediaType="application/x-www-form-urlencoded",
     * @OA\Schema(
     * type="object",
     * required={"email", "password"},
     * @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     * @OA\Property(property="password", type="string", format="password", example="password")
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Authentication successful",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="access_token", type="string", example="1|aBcDeFgHiJkLmNoPqRsTuVwXyZ123456"),
     * @OA\Property(property="token_type", type="string", example="Bearer")
     * ),
     * @OA\Property(property="message", type="string", example="Authentication successful.")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized, invalid credentials",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="data", type="object", nullable=true, example=null),
     * @OA\Property(property="message", type="string", example="Invalid login details")
     * )
     * )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            // 3. Use the errorResponse helper for failed logins
            return $this->errorResponse(
                'Invalid login details',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $request->user();
        $token = $user->createToken('api-token')->plainTextToken;

        $tokenData = [
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];

        // 4. Use the successResponse helper for successful logins
        return $this->successResponse(
            $tokenData,
            'Authentication successful.'
        );
    }
}
