<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Customers
    Route::delete('customers/destroy', 'CustomersController@massDestroy')->name('customers.massDestroy');
    Route::resource('customers', 'CustomersController');

    // Service Packages
    Route::delete('service-packages/destroy', 'ServicePackagesController@massDestroy')->name('service-packages.massDestroy');
    Route::resource('service-packages', 'ServicePackagesController');

    // Subscriptions
    Route::delete('subscriptions/destroy', 'SubscriptionsController@massDestroy')->name('subscriptions.massDestroy');
    Route::resource('subscriptions', 'SubscriptionsController');

    // Ticket Categories
    Route::delete('ticket-categories/destroy', 'TicketCategoriesController@massDestroy')->name('ticket-categories.massDestroy');
    Route::resource('ticket-categories', 'TicketCategoriesController');

    // Tickets
    Route::delete('tickets/destroy', 'TicketsController@massDestroy')->name('tickets.massDestroy');
    Route::resource('tickets', 'TicketsController');

    Route::post('tickets/{ticket}/update-status', 'TicketsController@updateStatus')->name('tickets.updateStatus');
    Route::post('tickets/{ticket}/update-assignments', 'TicketsController@updateAssignments')->name('tickets.updateAssignments');

    // Ticket Status Histories
    Route::delete('ticket-status-histories/destroy', 'TicketStatusHistoriesController@massDestroy')->name('ticket-status-histories.massDestroy');
    Route::resource('ticket-status-histories', 'TicketStatusHistoriesController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
