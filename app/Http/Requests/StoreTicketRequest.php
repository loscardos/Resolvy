<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTicketRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticket_create');
    }

    public function rules()
    {
        return [
            'subject' => [
                'string',
                'required',
            ],
            'customer_id' => [
                'required',
                'integer',
            ],
            'assigned_tos.*' => [
                'integer',
            ],
            'assigned_tos' => [
                'array',
            ],
            'description' => [
                'string',
                'nullable',
            ],
            'ticket_category_id' => [
                'integer',
                'nullable',
            ],
        ];
    }
}
