<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\SubscriptionRepositoryInterface;
use App\Repositories\TicketCategoryRepositoryInterface;
use App\Repositories\TicketRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketsController extends Controller
{
    private $customerRepository;
    private $subscriptionRepository;
    private $ticketCategoryRepository;
    private $ticketRepository;
    private $userRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
        TicketCategoryRepositoryInterface $ticketCategoryRepository,
        TicketRepositoryInterface $ticketRepository,
        UserRepositoryInterface $userRepository,
    )
    {
        $this->customerRepository = $customerRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->ticketCategoryRepository = $ticketCategoryRepository;
        $this->ticketRepository = $ticketRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ticket::with(['customer', 'subscription', 'ticket_category', 'assigned_tos'])->select(sprintf('%s.*', (new Ticket)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_show';
                $editGate      = 'ticket_edit';
                $deleteGate    = 'ticket_delete';
                $crudRoutePart = 'tickets';

                return view('partials.datatablesActions', compact('viewGate', 'editGate', 'deleteGate', 'crudRoutePart', 'row'));
            });

            $table->editColumn('ticket_no', function ($row) {
                return '<a href="' . route('admin.tickets.show', $row->id) . '" class="font-weight-bold">' . $row->ticket_no . '</a>';
            });

            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });

            $table->addColumn('customer_customer_code', function ($row) {
                return $row->customer ? '<span class="badge badge-secondary">' . $row->customer->customer_code . '</span>' : '';
            });

            $table->editColumn('status', function ($row) {
                $status = $row->status ? Ticket::STATUS_SELECT[$row->status] : '';
                $class = '';
                switch ($row->status) {
                    case 'open':
                        $class = 'badge-info';
                        break;
                    case 'in_progress':
                        $class = 'badge-primary';
                        break;
                    case 'closed':
                        $class = 'badge-success';
                        break;
                    default:
                        $class = 'badge-light';
                }
                return '<span class="badge ' . $class . '">' . $status . '</span>';
            });

            $table->editColumn('priority', function ($row) {
                $priority = $row->priority ? Ticket::PRIORITY_SELECT[$row->priority] : '';
                $class = '';
                switch ($row->priority) {
                    case 'high':
                    case 'critical':
                        $class = 'badge-danger';
                        break;
                    case 'medium':
                        $class = 'badge-warning';
                        break;
                    case 'low':
                        $class = 'badge-success';
                        break;
                    default:
                        $class = 'badge-light';
                }
                return '<span class="badge ' . $class . '">' . $priority . '</span>';
            });

            $table->editColumn('assigned_to', function ($row) {
                $labels = [];
                foreach ($row->assigned_tos as $assigned_to) {
                    $labels[] = sprintf('<span class="badge badge-info">%s</span>', $assigned_to->name);
                }
                return implode(' ', $labels);
            });

            $table->setRowClass(function ($row) {
                if ($row->priority == 'high' || $row->priority == 'critical') {
                    return 'table-danger';
                }
                if ($row->priority == 'medium') {
                    return 'table-warning';
                }
                return '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ticket_no', 'customer_customer_code', 'status', 'priority', 'assigned_to']);

            return $table->make(true);
        }

        $customers         = $this->customerRepository->all();
        $users             = $this->userRepository->all();

        return view('admin.tickets.index', compact('customers',  'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = $this->customerRepository->all()->pluck('customer_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = $this->subscriptionRepository->all()->pluck('start_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ticket_categories = $this->ticketCategoryRepository->all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_tos = $this->userRepository->all()->pluck('name', 'id');

        return view('admin.tickets.create', compact('assigned_tos', 'customers', 'subscriptions', 'ticket_categories'));
    }

    public function store(StoreTicketRequest $request)
    {
        $this->ticketRepository->create($request->all());

        return redirect()->route('admin.tickets.index');
    }

    public function edit(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = $this->customerRepository->all()->pluck('customer_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = $this->subscriptionRepository->all()->pluck('start_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ticket_categories = $this->ticketCategoryRepository->all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_tos = $this->userRepository->all()->pluck('name', 'id');

        $ticket->load('customer', 'subscription', 'ticket_category', 'assigned_tos');

        return view('admin.tickets.edit', compact('assigned_tos', 'customers', 'subscriptions', 'ticket', 'ticket_categories'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->ticketRepository->update($ticket->id, $request->all());

        return redirect()->route('admin.tickets.index');
    }

    public function show(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticket->load('customer', 'subscription', 'ticket_category', 'assigned_tos');

        $users = $this->userRepository->all()->pluck('name', 'id');

        return view('admin.tickets.show', compact('ticket', 'users'));
    }

    public function destroy(Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->ticketRepository->delete($ticket->id);

        return back();
    }

    public function massDestroy(MassDestroyTicketRequest $request)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->ticketRepository->deleteMany($request->input('ids'));

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate(['status' => 'required|string|in:' . implode(',', array_keys(Ticket::STATUS_SELECT))]);

        $success = $this->ticketRepository->updateStatus($ticket->id, $request->status);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Status update failed. The ticket may need to be resolved first.'], 422);
    }

    public function updateAssignments(Request $request, Ticket $ticket)
    {
        abort_if(Gate::denies('ticket_assign_pics'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->ticketRepository->assignUsers($ticket->id, $request->input('assigned_tos', []));

        $ticket->load('assigned_tos');
        $newBadges = view('partials.assignedUsersBadges', ['users' => $ticket->assigned_tos])->render();

        return response()->json(['success' => true, 'newBadges' => $newBadges]);
    }
}
