<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnumsApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Post(
     * path="/api/v1/enums",
     * operationId="getEnums",
     * tags={"Utilities"},
     * summary="Get a list of selectable enum values for a module",
     * description="Provides a generic way to fetch constant arrays (enums) from various models.",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Specify the module and type of enum to fetch",
     * @OA\JsonContent(
     * required={"module", "type"},
     * @OA\Property(
     * property="module",
     * type="string",
     * description="The name of the module",
     * enum={"customer", "subscription", "ticket", "service_package"},
     * example="subscription"
     * ),
     * @OA\Property(
     * property="type",
     * type="string",
     * description="The type of enum to fetch",
     * enum={"status", "priority", "type", "is_active"},
     * example="status"
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="object",
     * description="An object where keys are the enum values and values are the display labels.",
     * example={"active": "Active", "paused": "Paused", "cancelled": "Cancelled", "expired": "Expired"}
     * ),
     * @OA\Property(property="message", type="string", example="Enums retrieved successfully.")
     * )
     * ),
     * @OA\Response(response=404, description="The requested enum or module was not found")
     * )
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'type'   => 'required|string',
        ]);

        $moduleMapping = [
            'customer'        => \App\Models\Customer::class,
            'subscription'    => \App\Models\Subscription::class,
            'ticket'          => \App\Models\Ticket::class,
            'service_package' => \App\Models\ServicePackage::class,
        ];

        $module = strtolower($request->input('module'));
        $type = strtoupper($request->input('type'));

        if (!isset($moduleMapping[$module])) {
            return $this->errorResponse('Module not found.', Response::HTTP_NOT_FOUND);
        }

        $modelClass = $moduleMapping[$module];
        $constantName = "{$type}_SELECT";

        if (!defined("$modelClass::$constantName")) {
            return $this->errorResponse('Enum type not found for the specified module.', Response::HTTP_NOT_FOUND);
        }

        $enums = constant("$modelClass::$constantName");

        return $this->successResponse($enums, 'Enums retrieved successfully.');
    }
}
