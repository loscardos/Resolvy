<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubscriptionRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Customer;
use App\Models\ServicePackage;
use App\Models\Subscription;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('subscription_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subscription::with(['customer', 'package'])->select(sprintf('%s.*', (new Subscription)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'subscription_show';
                $editGate      = 'subscription_edit';
                $deleteGate    = 'subscription_delete';
                $crudRoutePart = 'subscriptions';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('customer_customer_code', function ($row) {
                return $row->customer ? $row->customer->customer_code : '';
            });

            $table->editColumn('customer.name', function ($row) {
                return $row->customer ? (is_string($row->customer) ? $row->customer : $row->customer->name) : '';
            });

            $table->editColumn('status', function ($row) {
                return $row->status ? Subscription::STATUS_SELECT[$row->status] : '';
            });
            $table->addColumn('package_name', function ($row) {
                return $row->package ? $row->package->name : '';
            });

            $table->editColumn('package.price', function ($row) {
                return $row->package ? (is_string($row->package) ? $row->package : $row->package->price) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'customer', 'package']);

            return $table->make(true);
        }

        $customers        = Customer::get();
        $service_packages = ServicePackage::get();

        return view('admin.subscriptions.index', compact('customers', 'service_packages'));
    }

    public function create()
    {
        abort_if(Gate::denies('subscription_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::pluck('customer_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $packages = ServicePackage::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subscriptions.create', compact('customers', 'packages'));
    }

    public function store(StoreSubscriptionRequest $request)
    {
        $subscription = Subscription::create($request->all());

        return redirect()->route('admin.subscriptions.index');
    }

    public function edit(Subscription $subscription)
    {
        abort_if(Gate::denies('subscription_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::pluck('customer_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $packages = ServicePackage::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscription->load('customer', 'package');

        return view('admin.subscriptions.edit', compact('customers', 'packages', 'subscription'));
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->all());

        return redirect()->route('admin.subscriptions.index');
    }

    public function show(Subscription $subscription)
    {
        abort_if(Gate::denies('subscription_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscription->load('customer', 'package');

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function destroy(Subscription $subscription)
    {
        abort_if(Gate::denies('subscription_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscription->delete();

        return back();
    }

    public function massDestroy(MassDestroySubscriptionRequest $request)
    {
        $subscriptions = Subscription::find(request('ids'));

        foreach ($subscriptions as $subscription) {
            $subscription->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
