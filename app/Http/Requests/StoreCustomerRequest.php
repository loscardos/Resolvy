<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('customer_create');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'type' => [
                'required',
            ],
            'contact_email' => [
                'string',
                'nullable',
            ],
            'contact_phone' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
            'start_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'end_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'subscription_status' => [
                'required',
            ],
            'package_id' => [
                'required',
                'integer',
            ],
            'notes' => [
                'string',
                'nullable',
            ],
        ];
    }
}
