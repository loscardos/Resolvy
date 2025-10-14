<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTicketAssignmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('ticket_assign_pics');
    }

    public function rules(): array
    {
        return [
            'assigned_tos'   => [
                'present',
                'array',
            ],
            'assigned_tos.*' => [
                'integer',
                'exists:users,id',
            ],
        ];
    }
}
