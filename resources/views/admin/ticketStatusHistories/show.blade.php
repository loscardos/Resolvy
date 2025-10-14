@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ticketStatusHistory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ticket-status-histories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketStatusHistory.fields.id') }}
                        </th>
                        <td>
                            {{ $ticketStatusHistory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketStatusHistory.fields.ticket') }}
                        </th>
                        <td>
                            {{ $ticketStatusHistory->ticket->ticket_no ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketStatusHistory.fields.from_status') }}
                        </th>
                        <td>
                            {{ $ticketStatusHistory->from_status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketStatusHistory.fields.to_status') }}
                        </th>
                        <td>
                            {{ $ticketStatusHistory->to_status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketStatusHistory.fields.updated_by') }}
                        </th>
                        <td>
                            {{ $ticketStatusHistory->updated_by->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketStatusHistory.fields.note') }}
                        </th>
                        <td>
                            {{ $ticketStatusHistory->note }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ticket-status-histories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection