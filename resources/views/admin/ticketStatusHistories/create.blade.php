@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.ticketStatusHistory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ticket-status-histories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="ticket_id">{{ trans('cruds.ticketStatusHistory.fields.ticket') }}</label>
                <select class="form-control select2 {{ $errors->has('ticket') ? 'is-invalid' : '' }}" name="ticket_id" id="ticket_id" required>
                    @foreach($tickets as $id => $entry)
                        <option value="{{ $id }}" {{ old('ticket_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ticket'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ticket') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketStatusHistory.fields.ticket_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="from_status">{{ trans('cruds.ticketStatusHistory.fields.from_status') }}</label>
                <input class="form-control {{ $errors->has('from_status') ? 'is-invalid' : '' }}" type="text" name="from_status" id="from_status" value="{{ old('from_status', '') }}" required>
                @if($errors->has('from_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('from_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketStatusHistory.fields.from_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="to_status">{{ trans('cruds.ticketStatusHistory.fields.to_status') }}</label>
                <input class="form-control {{ $errors->has('to_status') ? 'is-invalid' : '' }}" type="text" name="to_status" id="to_status" value="{{ old('to_status', '') }}" required>
                @if($errors->has('to_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('to_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketStatusHistory.fields.to_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="updated_by_id">{{ trans('cruds.ticketStatusHistory.fields.updated_by') }}</label>
                <select class="form-control select2 {{ $errors->has('updated_by') ? 'is-invalid' : '' }}" name="updated_by_id" id="updated_by_id" required>
                    @foreach($updated_bies as $id => $entry)
                        <option value="{{ $id }}" {{ old('updated_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('updated_by'))
                    <div class="invalid-feedback">
                        {{ $errors->first('updated_by') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketStatusHistory.fields.updated_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.ticketStatusHistory.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ticketStatusHistory.fields.note_helper') }}</span>
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