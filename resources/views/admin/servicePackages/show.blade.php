@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.servicePackage.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.service-packages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.id') }}
                        </th>
                        <td>
                            {{ $servicePackage->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.name') }}
                        </th>
                        <td>
                            {{ $servicePackage->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.bandwidth_mbps_down') }}
                        </th>
                        <td>
                            {{ $servicePackage->bandwidth_mbps_down }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.bandwidth_mbps_up') }}
                        </th>
                        <td>
                            {{ $servicePackage->bandwidth_mbps_up }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.price') }}
                        </th>
                        <td>
                            {{ $servicePackage->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.description') }}
                        </th>
                        <td>
                            {{ $servicePackage->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.servicePackage.fields.is_active') }}
                        </th>
                        <td>
                            {{ App\Models\ServicePackage::IS_ACTIVE_SELECT[$servicePackage->is_active] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.service-packages.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection