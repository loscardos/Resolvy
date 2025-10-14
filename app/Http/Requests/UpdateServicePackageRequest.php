<?php

namespace App\Http\Requests;

use App\Models\ServicePackage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateServicePackageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('service_package_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'bandwidth_mbps_down' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'bandwidth_mbps_up' => [
                'string',
                'required',
            ],
        ];
    }
}
