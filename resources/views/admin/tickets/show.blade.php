@extends('layouts.admin')
@section('content')

    <div class="d-flex justify-content-between mb-4">
        <div>
            <h3 class="mb-0">{{ trans('cruds.ticket.title_singular') }}: {{ $ticket->subject }}</h3>
            <span class="text-muted">Ticket #{{ $ticket->ticket_no }}</span>
        </div>
        <div>
            <a class="btn btn-info" href="{{ route('admin.tickets.edit', $ticket->id) }}">
                {{ trans('global.edit') }}
            </a>
            <a class="btn btn-primary" href="{{ route('admin.tickets.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Left Column --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">{{ trans('cruds.ticket.fields.description') }}</h5></div>
                <div class="card-body">
                    @if($ticket->description)
                        <p class="text-secondary">{{ $ticket->description }}</p>
                    @else
                        <p class="text-muted font-italic">No description provided.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Details</h5></div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tbody>
                        {{-- Status (Inline Edit) --}}
                        <tr>
                            <th width="40%">{{ trans('cruds.ticket.fields.status') }}</th>
                            <td>
                                <div id="status-display">
                                    <span class="badge badge-secondary">{{ App\Models\Ticket::STATUS_SELECT[$ticket->status] ?? '' }}</span>
                                    <a href="#" id="edit-status" class="ml-2 text-info"><i class="fas fa-pencil-alt fa-xs"></i></a>
                                </div>
                                <form id="status-form" action="{{ route('admin.tickets.updateStatus', $ticket->id) }}" method="POST" class="d-none">
                                    @csrf
                                    <select name="status" class="form-control form-control-sm">
                                        @foreach(App\Models\Ticket::STATUS_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ $ticket->status === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>

                        {{-- Priority --}}
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.priority') }}</th>
                            <td>
                                @php
                                    $priority_class = '';
                                    switch($ticket->priority) {
                                        case 'high':
                                            $priority_class = 'badge-danger';
                                            break;
                                        case 'medium':
                                            $priority_class = 'badge-warning';
                                            break;
                                        case 'low':
                                            $priority_class = 'badge-success';
                                            break;
                                        default:
                                            $priority_class = 'badge-light';
                                    }
                                @endphp
                                <span class="badge {{ $priority_class }}">{{ App\Models\Ticket::PRIORITY_SELECT[$ticket->priority] ?? '' }}</span>
                            </td>
                        </tr>

                        {{-- Customer --}}
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.customer') }}</th>
                            <td>
                                <a href="{{ route('admin.customers.show', $ticket->customer->id) }}">{{ $ticket->customer->name ?? '' }}</a>
                                <br>
                                <small class="text-muted">{{ $ticket->customer->customer_code ?? '' }}</small>
                            </td>
                        </tr>

                        {{-- Category --}}
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.ticket_category') }}</th>
                            <td>{{ $ticket->ticket_category->name ?? 'N/A' }}</td>
                        </tr>

                        {{-- Assigned To (Inline Edit) --}}
                        <tr>
                            <th>{{ trans('cruds.ticket.fields.assigned_to') }}</th>
                            <td>
                                <div id="assign-display">
                                    <div id="assigned-badges">
                                        @include('partials.assignedUsersBadges', ['users' => $ticket->assigned_tos])
                                    </div>
                                    <a href="#" id="edit-assign" class="ml-2 text-info"><i class="fas fa-pencil-alt fa-xs"></i></a>
                                </div>
                                <form id="assign-form" action="{{ route('admin.tickets.updateAssignments', $ticket->id) }}" method="POST" class="d-none">
                                    @csrf
                                    <select name="assigned_tos[]" class="form-control select2" multiple="multiple">
                                        @foreach($users as $id => $name)
                                            <option value="{{ $id }}" {{ $ticket->assigned_tos->contains($id) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-xs btn-primary">Save</button>
                                        <button type="button" id="cancel-assign" class="btn btn-xs btn-secondary">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            // --- Status Inline Edit ---
            $('#edit-status').on('click', function (e) {
                e.preventDefault();
                $('#status-display').addClass('d-none');
                $('#status-form').removeClass('d-none');
            });

            $('#status-form select[name="status"]').on('change', function () {
                $('#status-form').submit();
            });

            $('#status-form').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    method: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        // Update the badge text and class, then toggle visibility
                        let newStatus = form.find('select option:selected').text();
                        // You'll need to add more robust class switching based on the new status
                        $('#status-display .badge').text(newStatus);
                        $('#status-form').addClass('d-none');
                        $('#status-display').removeClass('d-none');
                        // You could add a success toastr notification here
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message || 'An error occurred.');
                        $('#status-form').addClass('d-none');
                        $('#status-display').removeClass('d-none');
                    }
                });
            });

            // --- Assign To Inline Edit ---
            $('#edit-assign').on('click', function (e) {
                e.preventDefault();
                $('#assign-display').addClass('d-none');
                $('#assign-form').removeClass('d-none');
                $('#assign-form .select2').select2(); // Re-initialize select2
            });

            $('#cancel-assign').on('click', function() {
                $('#assign-form').addClass('d-none');
                $('#assign-display').removeClass('d-none');
            });

            $('#assign-form').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    method: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        $('#assigned-badges').html(response.newBadges);
                        $('#assign-form').addClass('d-none');
                        $('#assign-display').removeClass('d-none');
                    }
                });
            });
        });
    </script>
@endsection
