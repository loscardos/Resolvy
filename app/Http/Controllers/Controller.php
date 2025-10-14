<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Resolvy API Documentation",
 * description="API documentation for the Resolvy helpdesk application",
 * @OA\Contact(
 * email="admin@example.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Main API Server"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * type="http",
 * scheme="bearer"
 * )
 *
 * @OA\Schema(
 * schema="StoreCustomerRequest",
 * title="Store Customer and Subscription Request",
 * required={"name", "type", "contact_phone", "status", "package_id", "start_date", "end_date", "subscription_status"},
 * @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(property="type", type="string", enum={"individual", "business"}, example="individual"),
 * @OA\Property(property="contact_email", type="string", format="email", nullable=true, example="john.doe@example.com"),
 * @OA\Property(property="contact_phone", type="string", example="123-456-7890"),
 * @OA\Property(property="status", type="string", enum={"active", "suspended", "cancelled"}, example="active"),
 * @OA\Property(property="package_id", type="integer", description="ID of the service package for the subscription", example=1),
 * @OA\Property(property="start_date", type="string", format="date", description="Subscription start date", example="2025-10-14"),
 * @OA\Property(property="end_date", type="string", format="date", description="Subscription end date", example="2026-10-14"),
 * @OA\Property(property="subscription_status", type="string", enum={"active", "paused", "cancelled", "expired"}, description="Status for the new subscription", example="active"),
 * @OA\Property(property="notes", type="string", nullable=true, description="Notes for the subscription")
 * )
 *
 * @OA\Schema(
 * schema="UpdateCustomerRequest",
 * title="Update Customer and Subscription Request",
 * required={"name", "type", "contact_phone", "status", "package_id", "start_date", "end_date", "subscription_status"},
 * @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(property="type", type="string", enum={"individual", "business"}, example="individual"),
 * @OA\Property(property="contact_email", type="string", format="email", nullable=true, example="john.doe@example.com"),
 * @OA\Property(property="contact_phone", type="string", example="123-456-7890"),
 * @OA\Property(property="status", type="string", enum={"active", "suspended", "cancelled"}, example="active"),
 * @OA\Property(property="package_id", type="integer", description="ID of the service package for the subscription", example=1),
 * @OA\Property(property="start_date", type="string", format="date", description="Subscription start date", example="2025-10-14"),
 * @OA\Property(property="end_date", type="string", format="date", description="Subscription end date", example="2026-10-14"),
 * @OA\Property(property="subscription_status", type="string", enum={"active", "paused", "cancelled", "expired"}, description="Status for the subscription", example="active"),
 * @OA\Property(property="notes", type="string", nullable=true, description="Notes for the subscription")
 * )
 *
 * @OA\Schema(
 * schema="CustomerResource",
 * type="object",
 * title="Customer Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="customer_code", type="string", readOnly=true, example="CUS-0000000001"),
 * @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(property="type", type="string", example="individual"),
 * @OA\Property(property="contact_email", type="string", format="email", example="john.doe@example.com"),
 * @OA\Property(property="contact_phone", type="string", example="123-456-7890"),
 * @OA\Property(property="status", type="string", example="active")
 * )
 *
 * @OA\Schema(
 * schema="PaginationMeta",
 * type="object",
 * title="Pagination Meta",
 * @OA\Property(property="current_page", type="integer", example=1),
 * @OA\Property(property="from", type="integer", example=1),
 * @OA\Property(property="last_page", type="integer", example=10),
 * @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/customers"),
 * @OA\Property(property="per_page", type="integer", example=15),
 * @OA\Property(property="to", type="integer", example=15),
 * @OA\Property(property="total", type="integer", example=150)
 * )
 *
 * @OA\Schema(
 * schema="PaginationLinks",
 * type="object",
 * title="Pagination Links",
 * @OA\Property(property="first", type="string", example="http://localhost:8000/api/v1/customers?page=1"),
 * @OA\Property(property="last", type="string", example="http://localhost:8000/api/v1/customers?page=10"),
 * @OA\Property(property="prev", type="string", nullable=true, example=null),
 * @OA\Property(property="next", type="string", example="http://localhost:8000/api/v1/customers?page=2")
 * )
 *
 * @OA\Schema(
 * schema="StorePermissionRequest",
 * type="object",
 * title="Store Permission Request",
 * required={"title"},
 * @OA\Property(property="title", type="string", description="The title of the permission", example="user_management_access")
 * )
 *
 * @OA\Schema(
 * schema="UpdatePermissionRequest",
 * type="object",
 * title="Update Permission Request",
 * required={"title"},
 * @OA\Property(property="title", type="string", description="The title of the permission", example="user_management_edit")
 * )
 *
 * @OA\Schema(
 * schema="PermissionResource",
 * type="object",
 * title="Permission Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="title", type="string", example="user_management_access")
 * )
 *
 * @OA\Schema(
 * schema="StoreRoleRequest",
 * type="object",
 * title="Store Role Request",
 * required={"title"},
 * @OA\Property(property="title", type="string", description="The title of the role", example="Manager"),
 * @OA\Property(
 * property="permissions",
 * type="array",
 * description="An array of permission IDs to assign to the role",
 * @OA\Items(type="integer", example=1)
 * )
 * )
 *
 * @OA\Schema(
 * schema="UpdateRoleRequest",
 * type="object",
 * title="Update Role Request",
 * required={"title"},
 * @OA\Property(property="title", type="string", description="The title of the role", example="Senior Manager"),
 * @OA\Property(
 * property="permissions",
 * type="array",
 * description="An array of permission IDs to assign to the role",
 * @OA\Items(type="integer", example=2)
 * )
 * )
 *
 * @OA\Schema(
 * schema="RoleResource",
 * type="object",
 * title="Role Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="title", type="string", example="Administrator"),
 * @OA\Property(
 * property="permissions",
 * type="array",
 * @OA\Items(ref="#/components/schemas/PermissionResource")
 * )
 * )
 *
 * @OA\Schema(
 * schema="StoreServicePackageRequest",
 * type="object",
 * title="Store Service Package Request",
 * required={"name", "price"},
 * @OA\Property(property="name", type="string", description="The name of the service package", example="Gold Tier Support"),
 * @OA\Property(property="description", type="string", description="A description of the package", example="24/7 phone and email support"),
 * @OA\Property(property="price", type="number", format="float", description="The price of the package", example=99.99)
 * )
 *
 * @OA\Schema(
 * schema="UpdateServicePackageRequest",
 * type="object",
 * title="Update Service Package Request",
 * @OA\Property(property="name", type="string", description="The name of the service package", example="Platinum Tier Support"),
 * @OA\Property(property="description", type="string", description="A description of the package", example="Dedicated support agent and 24/7 phone support"),
 * @OA\Property(property="price", type="number", format="float", description="The price of the package", example=199.99)
 * )
 *
 * @OA\Schema(
 * schema="ServicePackageResource",
 * type="object",
 * title="Service Package Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="Gold Tier Support"),
 * @OA\Property(property="description", type="string", example="24/7 phone and email support"),
 * @OA\Property(property="price", type="number", format="float", example=99.99)
 * )
 *
 * @OA\Schema(
 * schema="StoreSubscriptionRequest",
 * type="object",
 * title="Store Subscription Request",
 * required={"customer_id", "package_id", "start_date", "end_date", "status"},
 * @OA\Property(property="customer_id", type="integer", description="ID of the customer", example=1),
 * @OA\Property(property="package_id", type="integer", description="ID of the service package", example=1),
 * @OA\Property(property="start_date", type="string", format="date", description="Subscription start date", example="2025-10-14"),
 * @OA\Property(property="end_date", type="string", format="date", description="Subscription end date", example="2026-10-14"),
 * @OA\Property(property="status", type="string", enum={"active", "paused", "cancelled", "expired"}, example="active"),
 * @OA\Property(property="notes", type="string", description="Additional notes", example="Initial setup complete.")
 * )
 *
 * @OA\Schema(
 * schema="UpdateSubscriptionRequest",
 * type="object",
 * title="Update Subscription Request",
 * @OA\Property(property="customer_id", type="integer", description="ID of the customer", example=1),
 * @OA\Property(property="package_id", type="integer", description="ID of the service package", example=1),
 * @OA\Property(property="start_date", type="string", format="date", description="Subscription start date", example="2025-10-14"),
 * @OA\Property(property="end_date", type="string", format="date", description="Subscription end date", example="2026-10-14"),
 * @OA\Property(property="status", type="string", enum={"active", "paused", "cancelled", "expired"}, example="active"),
 * @OA\Property(property="notes", type="string", description="Additional notes", example="Customer requested a pause.")
 * )
 *
 * @OA\Schema(
 * schema="SubscriptionResource",
 * type="object",
 * title="Subscription Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="start_date", type="string", format="date", example="2025-10-14"),
 * @OA\Property(property="end_date", type="string", format="date", example="2026-10-14"),
 * @OA\Property(property="status", type="string", example="active"),
 * @OA\Property(property="notes", type="string", example="Initial setup complete."),
 * @OA\Property(property="customer", ref="#/components/schemas/CustomerResource"),
 * @OA\Property(property="package", ref="#/components/schemas/ServicePackageResource")
 * )
 *
 * @OA\Schema(
 * schema="StoreTicketCategoryRequest",
 * type="object",
 * title="Store Ticket Category Request",
 * required={"name"},
 * @OA\Property(property="name", type="string", description="The name of the ticket category", example="Technical Support")
 * )
 *
 * @OA\Schema(
 * schema="UpdateTicketCategoryRequest",
 * type="object",
 * title="Update Ticket Category Request",
 * @OA\Property(property="name", type="string", description="The name of the ticket category", example="Billing Inquiry")
 * )
 *
 * @OA\Schema(
 * schema="TicketCategoryResource",
 * type="object",
 * title="Ticket Category Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="Technical Support")
 * )
 *
 * @OA\Schema(
 * schema="UserResource",
 * type="object",
 * title="User Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="John Doe"),
 * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com")
 * )
 *
 * @OA\Schema(
 * schema="StoreTicketRequest",
 * type="object",
 * title="Store Ticket Request",
 * required={"subject", "customer_id", "priority"},
 * @OA\Property(property="subject", type="string", description="The subject of the ticket", example="Cannot connect to the server"),
 * @OA\Property(property="description", type="string", description="Detailed description of the issue", example="When I try to log in, I get a connection error message."),
 * @OA\Property(property="customer_id", type="integer", description="ID of the customer reporting the issue", example=1),
 * @OA\Property(property="ticket_category_id", type="integer", description="ID of the ticket category", example=1),
 * @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="medium"),
 * @OA\Property(
 * property="assigned_tos",
 * type="array",
 * description="An array of user IDs to assign to the ticket",
 * @OA\Items(type="integer", example=1)
 * )
 * )
 *
 * @OA\Schema(
 * schema="UpdateTicketRequest",
 * type="object",
 * title="Update Ticket Request",
 * @OA\Property(property="subject", type="string", description="The subject of the ticket", example="Cannot connect to the server"),
 * @OA\Property(property="description", type="string", description="Detailed description of the issue", example="When I try to log in, I get a connection error message."),
 * @OA\Property(property="customer_id", type="integer", description="ID of the customer reporting the issue", example=1),
 * @OA\Property(property="ticket_category_id", type="integer", description="ID of the ticket category", example=1),
 * @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="medium"),
 * @OA\Property(
 * property="assigned_tos",
 * type="array",
 * description="An array of user IDs to assign to the ticket",
 * @OA\Items(type="integer", example=1)
 * )
 * )
 *
 * @OA\Schema(
 * schema="UpdateTicketStatusRequest",
 * type="object",
 * title="Update Ticket Status Request",
 * required={"status"},
 * @OA\Property(property="status", type="string", enum={"open", "in_progress", "resolved", "closed"}, example="in_progress")
 * )
 *
 * @OA\Schema(
 * schema="UpdateTicketAssignmentsRequest",
 * type="object",
 * title="Update Ticket Assignments Request",
 * required={"assigned_tos"},
 * @OA\Property(
 * property="assigned_tos",
 * type="array",
 * description="An array of user IDs to assign. Send an empty array to unassign all.",
 * @OA\Items(type="integer", example=1)
 * )
 * )
 *
 * @OA\Schema(
 * schema="TicketResource",
 * type="object",
 * title="Ticket Resource",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="ticket_no", type="string", readOnly=true, example="SUP-0000000001"),
 * @OA\Property(property="subject", type="string", example="Cannot connect to the server"),
 * @OA\Property(property="description", type="string", example="When I try to log in..."),
 * @OA\Property(property="status", type="string", example="open"),
 * @OA\Property(property="priority", type="string", example="medium"),
 * @OA\Property(property="customer", ref="#/components/schemas/CustomerResource"),
 * @OA\Property(property="subscription", ref="#/components/schemas/SubscriptionResource"),
 * @OA\Property(property="ticket_category", ref="#/components/schemas/TicketCategoryResource"),
 * @OA\Property(property="assigned_tos", type="array", @OA\Items(ref="#/components/schemas/UserResource"))
 * )
 *
 * @OA\Schema(
 * schema="StoreUserRequest",
 * type="object",
 * title="Store User Request",
 * required={"name", "email", "password", "roles"},
 * @OA\Property(property="name", type="string", example="Jane Doe"),
 * @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
 * @OA\Property(property="password", type="string", format="password", example="password"),
 * @OA\Property(property="approved", type="boolean", example=true),
 * @OA\Property(
 * property="roles",
 * type="array",
 * description="An array of role IDs to assign to the user",
 * @OA\Items(type="integer", example=2)
 * )
 * )
 *
 * @OA\Schema(
 * schema="UpdateUserRequest",
 * type="object",
 * title="Update User Request",
 * @OA\Property(property="name", type="string", example="Jane Doe"),
 * @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
 * @OA\Property(property="password", type="string", format="password", description="Provide a new password to change it", example="new_password"),
 * @OA\Property(property="approved", type="boolean", example=true),
 * @OA\Property(
 * property="roles",
 * type="array",
 * description="An array of role IDs to assign to the user",
 * @OA\Items(type="integer", example=2)
 * )
 * )
 *
 * @OA\Schema(
 * schema="UserResourceWithRoles",
 * type="object",
 * title="User Resource with Roles",
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="Jane Doe"),
 * @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
 * @OA\Property(property="email_verified_at", type="string", format="date-time", readOnly=true, example="2025-10-14T10:00:00.000000Z"),
 * @OA\Property(property="approved", type="boolean", example=true),
 * @OA\Property(
 * property="roles",
 * type="array",
 * @OA\Items(ref="#/components/schemas/RoleResource")
 * )
 * )
 *
 * @OA\Tag(name="Utilities", description="General utility endpoints")
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
