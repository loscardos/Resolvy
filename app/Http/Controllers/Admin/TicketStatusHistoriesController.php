<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTicketStatusHistoryRequest;
use App\Http\Requests\StoreTicketStatusHistoryRequest;
use App\Http\Requests\UpdateTicketStatusHistoryRequest;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketStatusHistoriesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_status_history_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TicketStatusHistory::with(['ticket', 'updated_by'])->select(sprintf('%s.*', (new TicketStatusHistory)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_status_history_show';
                $editGate      = 'ticket_status_history_edit';
                $deleteGate    = 'ticket_status_history_delete';
                $crudRoutePart = 'ticket-status-histories';

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
            $table->addColumn('ticket_ticket_no', function ($row) {
                return $row->ticket ? $row->ticket->ticket_no : '';
            });

            $table->editColumn('ticket.subject', function ($row) {
                return $row->ticket ? (is_string($row->ticket) ? $row->ticket : $row->ticket->subject) : '';
            });
            $table->editColumn('from_status', function ($row) {
                return $row->from_status ? $row->from_status : '';
            });
            $table->editColumn('to_status', function ($row) {
                return $row->to_status ? $row->to_status : '';
            });
            $table->addColumn('updated_by_name', function ($row) {
                return $row->updated_by ? $row->updated_by->name : '';
            });

            $table->editColumn('updated_by.email', function ($row) {
                return $row->updated_by ? (is_string($row->updated_by) ? $row->updated_by : $row->updated_by->email) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ticket', 'updated_by']);

            return $table->make(true);
        }

        $tickets = Ticket::get();
        $users   = User::get();

        return view('admin.ticketStatusHistories.index', compact('tickets', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_status_history_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::pluck('ticket_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $updated_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ticketStatusHistories.create', compact('tickets', 'updated_bies'));
    }

    public function store(StoreTicketStatusHistoryRequest $request)
    {
        $ticketStatusHistory = TicketStatusHistory::create($request->all());

        return redirect()->route('admin.ticket-status-histories.index');
    }

    public function edit(TicketStatusHistory $ticketStatusHistory)
    {
        abort_if(Gate::denies('ticket_status_history_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::pluck('ticket_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $updated_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ticketStatusHistory->load('ticket', 'updated_by');

        return view('admin.ticketStatusHistories.edit', compact('ticketStatusHistory', 'tickets', 'updated_bies'));
    }

    public function update(UpdateTicketStatusHistoryRequest $request, TicketStatusHistory $ticketStatusHistory)
    {
        $ticketStatusHistory->update($request->all());

        return redirect()->route('admin.ticket-status-histories.index');
    }

    public function show(TicketStatusHistory $ticketStatusHistory)
    {
        abort_if(Gate::denies('ticket_status_history_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketStatusHistory->load('ticket', 'updated_by');

        return view('admin.ticketStatusHistories.show', compact('ticketStatusHistory'));
    }

    public function destroy(TicketStatusHistory $ticketStatusHistory)
    {
        abort_if(Gate::denies('ticket_status_history_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketStatusHistory->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketStatusHistoryRequest $request)
    {
        $ticketStatusHistories = TicketStatusHistory::find(request('ids'));

        foreach ($ticketStatusHistories as $ticketStatusHistory) {
            $ticketStatusHistory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
