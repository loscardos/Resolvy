<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('ticket_edit');
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:' . implode(',', array_keys(Ticket::STATUS_SELECT)),
            ],
        ];
    }
}
