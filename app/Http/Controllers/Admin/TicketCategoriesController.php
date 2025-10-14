<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTicketCategoryRequest;
use App\Http\Requests\StoreTicketCategoryRequest;
use App\Http\Requests\UpdateTicketCategoryRequest;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TicketCategoriesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('ticket_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TicketCategory::query()->select(sprintf('%s.*', (new TicketCategory)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_category_show';
                $editGate      = 'ticket_category_edit';
                $deleteGate    = 'ticket_category_delete';
                $crudRoutePart = 'ticket-categories';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.ticketCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ticketCategories.create');
    }

    public function store(StoreTicketCategoryRequest $request)
    {
        $ticketCategory = TicketCategory::create($request->all());

        return redirect()->route('admin.ticket-categories.index');
    }

    public function edit(TicketCategory $ticketCategory)
    {
        abort_if(Gate::denies('ticket_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ticketCategories.edit', compact('ticketCategory'));
    }

    public function update(UpdateTicketCategoryRequest $request, TicketCategory $ticketCategory)
    {
        $ticketCategory->update($request->all());

        return redirect()->route('admin.ticket-categories.index');
    }

    public function show(TicketCategory $ticketCategory)
    {
        abort_if(Gate::denies('ticket_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ticketCategories.show', compact('ticketCategory'));
    }

    public function destroy(TicketCategory $ticketCategory)
    {
        abort_if(Gate::denies('ticket_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ticketCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyTicketCategoryRequest $request)
    {
        $ticketCategories = TicketCategory::find(request('ids'));

        foreach ($ticketCategories as $ticketCategory) {
            $ticketCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
