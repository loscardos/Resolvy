@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between mb-4">
        <h3 class="mb-0">{{ trans('global.all') }} {{ trans('cruds.customer.title') }}</h3>
        @can('customer_create')
            <div>
                <a class="btn btn-primary" href="{{ route('admin.customers.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.customer.title_singular') }}
                </a>
            </div>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Customer">
                <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.customer.fields.customer_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.customer.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.customer.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.customer.fields.contact_phone') }}
                    </th>
                    <th>
                        {{ trans('cruds.customer.fields.status') }}
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
                        <select class="form-control search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Customer::TYPE_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="form-control search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\Customer::STATUS_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
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
            @can('customer_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.customers.massDestroy') }}",
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
                ajax: "{{ route('admin.customers.index') }}",
                columns: [
                    {data: 'placeholder', name: 'placeholder'},
                    {data: 'customer_code', name: 'customer_code'},
                    {data: 'name', name: 'name'},
                    {data: 'type', name: 'type'},
                    {data: 'contact_phone', name: 'contact_phone'},
                    {data: 'status', name: 'status'},
                    {data: 'actions', name: '{{ trans('global.actions') }}'}
                ],
                orderCellsTop: true,
                order: [[2, 'desc']],
                pageLength: 10,
            };
            let table = $('.datatable-Customer').DataTable(dtOverrideGlobals);
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
