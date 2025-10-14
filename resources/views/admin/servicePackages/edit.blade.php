@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.servicePackage.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.service-packages.update", [$servicePackage->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.servicePackage.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $servicePackage->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.servicePackage.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="bandwidth_mbps_down">{{ trans('cruds.servicePackage.fields.bandwidth_mbps_down') }}</label>
                <input class="form-control {{ $errors->has('bandwidth_mbps_down') ? 'is-invalid' : '' }}" type="number" name="bandwidth_mbps_down" id="bandwidth_mbps_down" value="{{ old('bandwidth_mbps_down', $servicePackage->bandwidth_mbps_down) }}" step="1" required>
                @if($errors->has('bandwidth_mbps_down'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bandwidth_mbps_down') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.servicePackage.fields.bandwidth_mbps_down_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="bandwidth_mbps_up">{{ trans('cruds.servicePackage.fields.bandwidth_mbps_up') }}</label>
                <input class="form-control {{ $errors->has('bandwidth_mbps_up') ? 'is-invalid' : '' }}" type="text" name="bandwidth_mbps_up" id="bandwidth_mbps_up" value="{{ old('bandwidth_mbps_up', $servicePackage->bandwidth_mbps_up) }}" required>
                @if($errors->has('bandwidth_mbps_up'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bandwidth_mbps_up') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.servicePackage.fields.bandwidth_mbps_up_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price">{{ trans('cruds.servicePackage.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $servicePackage->price) }}" step="0.01">
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.servicePackage.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.servicePackage.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $servicePackage->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.servicePackage.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.servicePackage.fields.is_active') }}</label>
                <select class="form-control {{ $errors->has('is_active') ? 'is-invalid' : '' }}" name="is_active" id="is_active">
                    <option value disabled {{ old('is_active', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ServicePackage::IS_ACTIVE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('is_active', $servicePackage->is_active) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('is_active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_active') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.servicePackage.fields.is_active_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection