@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between mb-4">
        <h3 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.ticket.title_singular') }}</h3>
    </div>

    <form method="POST" action="{{ route("admin.tickets.update", [$ticket->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <h5>Ticket Details</h5>
                        <hr>
                        <div class="form-group">
                            <label class="required" for="subject">{{ trans('cruds.ticket.fields.subject') }}</label>
                            <input class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject" id="subject" value="{{ old('subject', $ticket->subject) }}" required>
                            @if($errors->has('subject'))
                                <div class="invalid-feedback">{{ $errors->first('subject') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.ticket.fields.description') }}</label>
                            <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description" rows="6">{{ old('description', $ticket->description) }}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h5>Classification</h5>
                        <hr>
                        <div class="form-group">
                            <label class="required" for="customer_id">{{ trans('cruds.ticket.fields.customer') }}</label>
                            <select class="form-control select2 {{ $errors->has('customer_id') ? 'is-invalid' : '' }}" name="customer_id" id="customer_id" required>
                                @foreach($customers as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('customer_id') ? old('customer_id') : $ticket->customer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('customer_id'))
                                <div class="invalid-feedback">{{ $errors->first('customer_id') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="ticket_category_id">{{ trans('cruds.ticket.fields.ticket_category') }}</label>
                            <select class="form-control select2 {{ $errors->has('ticket_category_id') ? 'is-invalid' : '' }}" name="ticket_category_id" id="ticket_category_id">
                                @foreach($ticket_categories as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('ticket_category_id') ? old('ticket_category_id') : $ticket->ticket_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ticket_category_id'))
                                <div class="invalid-feedback">{{ $errors->first('ticket_category_id') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.ticket.fields.priority') }}</label>
                            <select class="form-control {{ $errors->has('priority') ? 'is-invalid' : '' }}" name="priority" id="priority" required>
                                @foreach(App\Models\Ticket::PRIORITY_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('priority', $ticket->priority) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('priority'))
                                <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="assigned_tos">{{ trans('cruds.ticket.fields.assigned_to') }}</label>
                            <div class="mb-2">
                                <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('assigned_tos') ? 'is-invalid' : '' }}" name="assigned_tos[]" id="assigned_tos" multiple>
                                @foreach($assigned_tos as $id => $assigned_to)
                                    <option value="{{ $id }}" {{ (in_array($id, old('assigned_tos', [])) || $ticket->assigned_tos->contains($id)) ? 'selected' : '' }}>{{ $assigned_to }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('assigned_tos'))
                                <div class="invalid-feedback">{{ $errors->first('assigned_tos') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary mr-2">{{ trans('global.cancel') }}</a>
                <button class="btn btn-primary" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </div>
    </form>
@endsection
