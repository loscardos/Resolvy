@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between mb-4">
        <h3 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.customer.title') }}</h3>
    </div>

    <form method="POST" action="{{ route("admin.customers.update", [$customer->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- Customer Details Column --}}
                    <div class="col-md-6">
                        <h5>Customer Details</h5>
                        <hr>
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.customer.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="contact_email">{{ trans('cruds.customer.fields.contact_email') }}</label>
                            <input class="form-control {{ $errors->has('contact_email') ? 'is-invalid' : '' }}" type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $customer->contact_email) }}">
                            @if($errors->has('contact_email'))
                                <div class="invalid-feedback">{{ $errors->first('contact_email') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required" for="contact_phone">{{ trans('cruds.customer.fields.contact_phone') }}</label>
                            <input class="form-control {{ $errors->has('contact_phone') ? 'is-invalid' : '' }}" type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $customer->contact_phone) }}" required>
                            @if($errors->has('contact_phone'))
                                <div class="invalid-feedback">{{ $errors->first('contact_phone') }}</div>
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ trans('cruds.customer.fields.type') }}</label>
                                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                                    @foreach(App\Models\Customer::TYPE_SELECT as $key => $label)
                                        <option value="{{ $key }}" {{ old('type', $customer->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('type'))
                                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">{{ trans('cruds.customer.fields.status') }}</label>
                                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                                    @foreach(App\Models\Customer::STATUS_SELECT as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $customer->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('status'))
                                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Subscription Details Column --}}
                    <div class="col-md-6">
                        <h5>Subscription Details</h5>
                        <hr>
                        <div class="form-group">
                            <label class="required" for="package_id">{{ trans('cruds.subscription.fields.package') }}</label>
                            <select class="form-control select2 {{ $errors->has('package_id') ? 'is-invalid' : '' }}" name="package_id" id="package_id" required>
                                @foreach($packages as $id => $entry)
                                    <option value="{{ $id }}" {{ old('package_id', $subscription->package_id) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('package_id'))
                                <div class="invalid-feedback">{{ $errors->first('package_id') }}</div>
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required" for="start_date">{{ trans('cruds.subscription.fields.start_date') }}</label>
                                <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text" name="start_date" id="start_date" value="{{ old('start_date', $subscription->start_date) }}" required>
                                @if($errors->has('start_date'))
                                    <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required" for="end_date">{{ trans('cruds.subscription.fields.end_date') }}</label>
                                <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text" name="end_date" id="end_date" value="{{ old('end_date', $subscription->end_date) }}" required>
                                @if($errors->has('end_date'))
                                    <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes">{{ trans('cruds.subscription.fields.notes') }}</label>
                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{{ old('notes', $subscription->notes) }}</textarea>
                            @if($errors->has('notes'))
                                <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.subscription.fields.status') }}</label>
                            <select class="form-control {{ $errors->has('subscription_status') ? 'is-invalid' : '' }}" name="subscription_status" id="subscription_status" required>
                                @foreach(App\Models\Subscription::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('subscription_status', $subscription->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('subscription_status'))
                                <div class="invalid-feedback">{{ $errors->first('subscription_status') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary mr-2">{{ trans('global.cancel') }}</a>
                <button class="btn btn-primary" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </div>
    </form>

@endsection
