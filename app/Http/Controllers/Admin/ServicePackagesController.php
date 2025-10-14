<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyServicePackageRequest;
use App\Http\Requests\StoreServicePackageRequest;
use App\Http\Requests\UpdateServicePackageRequest;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ServicePackagesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('service_package_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ServicePackage::query()->select(sprintf('%s.*', (new ServicePackage)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'service_package_show';
                $editGate      = 'service_package_edit';
                $deleteGate    = 'service_package_delete';
                $crudRoutePart = 'service-packages';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('bandwidth_mbps_down', function ($row) {
                return $row->bandwidth_mbps_down ? $row->bandwidth_mbps_down : '';
            });
            $table->editColumn('bandwidth_mbps_up', function ($row) {
                return $row->bandwidth_mbps_up ? $row->bandwidth_mbps_up : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('is_active', function ($row) {
                return $row->is_active ? ServicePackage::IS_ACTIVE_SELECT[$row->is_active] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.servicePackages.index');
    }

    public function create()
    {
        abort_if(Gate::denies('service_package_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.servicePackages.create');
    }

    public function store(StoreServicePackageRequest $request)
    {
        $servicePackage = ServicePackage::create($request->all());

        return redirect()->route('admin.service-packages.index');
    }

    public function edit(ServicePackage $servicePackage)
    {
        abort_if(Gate::denies('service_package_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.servicePackages.edit', compact('servicePackage'));
    }

    public function update(UpdateServicePackageRequest $request, ServicePackage $servicePackage)
    {
        $servicePackage->update($request->all());

        return redirect()->route('admin.service-packages.index');
    }

    public function show(ServicePackage $servicePackage)
    {
        abort_if(Gate::denies('service_package_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.servicePackages.show', compact('servicePackage'));
    }

    public function destroy(ServicePackage $servicePackage)
    {
        abort_if(Gate::denies('service_package_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servicePackage->delete();

        return back();
    }

    public function massDestroy(MassDestroyServicePackageRequest $request)
    {
        $servicePackages = ServicePackage::find(request('ids'));

        foreach ($servicePackages as $servicePackage) {
            $servicePackage->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
