<?php

namespace App\Http\Requests;

use App\Models\TicketStatusHistory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTicketStatusHistoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticket_status_history_create');
    }

    public function rules()
    {
        return [
            'ticket_id' => [
                'required',
                'integer',
            ],
            'from_status' => [
                'string',
                'required',
            ],
            'to_status' => [
                'string',
                'required',
            ],
            'updated_by_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
