<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1\Auth'], function () {
    Route::post('login', 'LoginApiController@login');
});

Route::group([
    'prefix' => 'v1',
    'as' => 'api.',
    'namespace' => 'Api\V1\Admin',
    'middleware' => ['auth:sanctum']
], function () {

    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Customers
    Route::apiResource('customers', 'CustomersApiController');
    Route::get('customers/by-code/{customer_code}', 'CustomersApiController@showByCode')->name('customers.showByCode');

    // Service Packages
    Route::apiResource('service-packages', 'ServicePackagesApiController');

    // Subscriptions
    Route::apiResource('subscriptions', 'SubscriptionsApiController');

    // Ticket Categories
    Route::apiResource('ticket-categories', 'TicketCategoriesApiController');

    // Tickets
    Route::post('tickets/{id}/status', 'TicketsApiController@updateStatus')->name('tickets.updateStatus');
    Route::post('tickets/{id}/assignments', 'TicketsApiController@updateAssignments')->name('tickets.updateAssignments');
    Route::apiResource('tickets', 'TicketsApiController');

    // Enums
    Route::post('enums', 'EnumsApiController')->name('enums');
});
