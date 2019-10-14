<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\FormRequest;

class AuthorizationsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key'       => 'required|string',
            'code'      => 'required|string',
        ];
    }
}
