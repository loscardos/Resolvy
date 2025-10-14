<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\ServicePackageRepositoryInterface;
use App\Repositories\SubscriptionRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CustomersController extends Controller
{
    private $customerRepository;
    private $servicePackageRepository;
    private $subscriptionRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        ServicePackageRepositoryInterface $servicePackageRepository,
        SubscriptionRepositoryInterface $subscriptionRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->servicePackageRepository = $servicePackageRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Customer::query()->select(sprintf('%s.*', (new Customer)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'customer_show';
                $editGate      = 'customer_edit';
                $deleteGate    = 'customer_delete';
                $crudRoutePart = 'customers';

                // Recommended: Use the dropdown actions menu from the previous response
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id;
            });

            // --- STYLING ADDED ---
            $table->editColumn('customer_code', function ($row) {
                return '<span class="badge badge-light">' . $row->customer_code . '</span>';
            });

            // --- STYLING ADDED ---
            $table->editColumn('name', function ($row) {
                return $row->name;
            });

            $table->editColumn('type', function ($row) {
                return $row->type ? Customer::TYPE_SELECT[$row->type] : '';
            });

            $table->editColumn('contact_phone', function ($row) {
                return $row->contact_phone ? $row->contact_phone : '';
            });

            // --- STYLING ADDED ---
            $table->editColumn('status', function ($row) {
                $status = $row->status ? Customer::STATUS_SELECT[$row->status] : '';
                $class = '';
                switch ($row->status) {
                    case 'active':
                        $class = 'badge-success';
                        break;
                    case 'suspended':
                        $class = 'badge-warning';
                        break;
                    case 'cancelled':
                        $class = 'badge-danger';
                        break;
                    default:
                        $class = 'badge-secondary';
                }
                return '<span class="badge ' . $class . '">' . $status . '</span>';
            });

            // --- STYLING ADDED: Add class to the entire row ---
            $table->setRowClass(function ($row) {
                return $row->status == 'cancelled' ? 'table-danger' : '';
            });

            // --- We need to allow HTML in our new styled columns ---
            $table->rawColumns(['actions', 'placeholder', 'customer_code', 'name', 'status']);

            return $table->make(true);
        }

        return view('admin.customers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $packages = $this->servicePackageRepository->all()->pluck('name', 'id');

        return view('admin.customers.create', compact('packages'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->customerRepository->createWithSubscription($request->all());

        return redirect()->route('admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $packages = $this->servicePackageRepository->all()->pluck('name', 'id');

        $subscription = $this->subscriptionRepository->findByCustomerId($customer->id)
            ->first();

        return view('admin.customers.edit', compact('customer', 'subscription', 'packages'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->customerRepository->updateWithSubscription($customer->id, $request->all());

        return redirect()->route('admin.customers.index');
    }

    public function show(Customer $customer)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer = $this->customerRepository->find($customer->id, ['*'], ['subscriptions.package']);

        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->customerRepository->delete($customer->id);

        return back();
    }

    public function massDestroy(MassDestroyCustomerRequest $request)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->customerRepository->deleteMany($request->input('ids'));

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
