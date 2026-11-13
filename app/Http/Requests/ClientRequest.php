<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nomprenom'=>['bail','required','string','min:6'], 
            'email'=>['bail','required','email','between:5,250'],
            'password'=>['bail','required','string','between:5,250'],
            'tel'=>['bail','required','string','between:5,30']
            
        ];
    }
}
