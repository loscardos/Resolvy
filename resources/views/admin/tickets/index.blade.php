@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between mb-4">
        <h3 class="mb-0">{{ trans('global.all') }} {{ trans('cruds.ticket.title') }}</h3>
        @can('ticket_create')
            <div>
                <a class="btn btn-primary" href="{{ route('admin.tickets.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
                </a>
            </div>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Ticket">
                <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.ticket_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.subject') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.customer') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.priority') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.assigned_to') }}
                    </th>
                    <th style="width: 60px">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="form-control search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="form-control search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="form-control search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($customers as $key => $item)
                                <option value="{{ $item->customer_code }}">{{ $item->customer_code }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Ticket::STATUS_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Ticket::PRIORITY_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-control search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($users as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('ticket_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.tickets.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).data(), function (entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.tickets.index') }}",
                columns: [
                    {data: 'placeholder', name: 'placeholder'},
                    {data: 'ticket_no', name: 'ticket_no'},
                    {data: 'subject', name: 'subject'},
                    {data: 'customer_customer_code', name: 'customer.customer_code'},
                    {data: 'status', name: 'status'},
                    {data: 'priority', name: 'priority'},
                    {data: 'assigned_to', name: 'assigned_tos.name'},
                    {data: 'actions', name: '{{ trans('global.actions') }}'}
                ],
                orderCellsTop: true,
                order: [[2, 'asc']],
                pageLength: 10,
            };
            let table = $('.datatable-Ticket').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            let visibleColumnsIndexes = null;
            $('.datatable thead').on('input', '.search', function () {
                let strict = $(this).attr('strict') || false
                let value = strict && this.value ? "^" + this.value + "$" : this.value

                let index = $(this).parent().index()
                if (visibleColumnsIndexes !== null) {
                    index = visibleColumnsIndexes[index]
                }

                table
                    .column(index)
                    .search(value, strict)
                    .draw()
            });
            table.on('column-visibility.dt', function (e, settings, column, state) {
                visibleColumnsIndexes = []
                table.columns(":visible").every(function (colIdx) {
                    visibleColumnsIndexes.push(colIdx);
                });
            })
        });

    </script>
@endsection
