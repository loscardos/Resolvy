<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        {{-- Optional: Global Search --}}
        <li class="px-3 py-2">
            <select class="searchable-field form-control"></select>
        </li>

        <li class="c-sidebar-nav-title">{{ trans('global.dashboard') }}</li>

        {{-- Dashboard --}}
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                {{ trans('global.dashboard') }}
            </a>
        </li>

        {{-- User Management --}}
        @can('user_management_access')
            <li class="c-sidebar-nav-title">{{ trans('cruds.userManagement.title') }}</li>

            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") || request()->is("admin/roles*") || request()->is("admin/users*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon"></i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-shield c-sidebar-nav-icon"></i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-tag c-sidebar-nav-icon"></i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- Customer Module --}}
        @can('customer_module_access')
            <li class="c-sidebar-nav-title">{{ trans('global.manage') }} Ticketings</li>

            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/customers*") || request()->is("admin/service-packages*") || request()->is("admin/subscriptions*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-address-book c-sidebar-nav-icon"></i>
                    {{ trans('cruds.customerModule.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('customer_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.customers.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/customers*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-tie c-sidebar-nav-icon"></i>
                                {{ trans('cruds.customer.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('service_package_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.service-packages.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/service-packages*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon"></i>
                                {{ trans('cruds.servicePackage.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('subscription_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.subscriptions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subscriptions*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-invoice-dollar c-sidebar-nav-icon"></i>
                                {{ trans('cruds.subscription.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        {{-- Ticket Module --}}
        @can('ticket_module_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/ticket-categories*") || request()->is("admin/tickets*") || request()->is("admin/ticket-status-histories*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-headset c-sidebar-nav-icon"></i>
                    {{ trans('cruds.ticketModule.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('ticket_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ticket-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ticket-categories*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-tags c-sidebar-nav-icon"></i>
                                {{ trans('cruds.ticketCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ticket_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.tickets.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/tickets*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-ticket-alt c-sidebar-nav-icon"></i>
                                {{ trans('cruds.ticket.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('ticket_status_history_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ticket-status-histories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/ticket-status-histories*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-history c-sidebar-nav-icon"></i>
                                {{ trans('cruds.ticketStatusHistory.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        <li class="c-sidebar-nav-title">{{ trans('global.utility') }} </li>

        {{-- Profile & Logout --}}
        @can('profile_password_edit')
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->is('profile/password*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                    <i class="fa-fw fas fa-key c-sidebar-nav-icon"></i>
                    {{ trans('global.change_password') }}
                </a>
            </li>
        @endcan

        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
