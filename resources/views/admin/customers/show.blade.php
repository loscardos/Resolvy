@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between mb-4">
        <h3 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.customer.title') }}</h3>
        <div>
            @can('customer_edit')
            <a class="btn btn-info" href="{{ route('admin.customers.edit', $customer->id) }}">
                {{ trans('global.edit') }}
            </a>
            @endcan

            <a class="btn btn-primary" href="{{ route('admin.customers.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-hover">
                        <tbody>
                        <tr>
                            <th width="30%">{{ trans('cruds.customer.fields.customer_code') }}</th>
                            <td><span class="badge badge-secondary">{{ $customer->customer_code }}</span></td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.customer.fields.name') }}</th>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.customer.fields.type') }}</th>
                            <td>{{ App\Models\Customer::TYPE_SELECT[$customer->type] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.customer.fields.contact_email') }}</th>
                            <td><a href="mailto:{{ $customer->contact_email }}">{{ $customer->contact_email }}</a></td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.customer.fields.contact_phone') }}</th>
                            <td>{{ $customer->contact_phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.customer.fields.status') }}</th>
                            <td>
                                @if($customer->status == 'active')
                                    <span class="badge badge-success">{{ App\Models\Customer::STATUS_SELECT[$customer->status] ?? '' }}</span>
                                @else
                                    <span class="badge badge-danger">{{ App\Models\Customer::STATUS_SELECT[$customer->status] ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            @forelse($customer->subscriptions as $subscription)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Current Subscription</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-hover">
                            <tbody>
                            <tr>
                                <th width="30%">Package Name</th>
                                <td><strong>{{ $subscription->package->name ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Package Price</th>
                                <td>IDR {{ number_format($subscription->package->price ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Subscription Status</th>
                                <td>
                                    @if($subscription->status == 'active')
                                        <span class="badge badge-success">{{ App\Models\Subscription::STATUS_SELECT[$subscription->status] ?? '' }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ App\Models\Subscription::STATUS_SELECT[$subscription->status] ?? '' }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <td>{{ $subscription->start_date }}</td>
                            </tr>
                            <tr>
                                <th>End Date</th>
                                <td>{{ $subscription->end_date }}</td>
                            </tr>
                            <tr>
                                <th>Notes</th>
                                <td class="text-muted">{{ $subscription->notes ?? 'No notes provided.' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-0">This customer does not have any subscriptions.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
