<?php

namespace App\Http\Requests;

use App\Models\TicketStatusHistory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTicketStatusHistoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ticket_status_history_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ticket_status_histories,id',
        ];
    }
}
