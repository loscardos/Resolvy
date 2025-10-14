<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('customer_edit');
    }

    public function rules()
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
