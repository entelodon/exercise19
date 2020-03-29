<?php

namespace App\Company\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCompanyDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dateFrom' => ['required', 'date'],
            'email' => ['required', 'email'],
            'dateTo' => ['required', 'date', 'after_or_equal:dateFrom'],
            'symbol' => ['required', 'exists:companies,symbol']
        ];
    }
}
