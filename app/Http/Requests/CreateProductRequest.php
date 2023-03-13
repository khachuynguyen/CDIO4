<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class CreateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_name'=>'required|min:6|unique:products,product_name',
            'avatar'=>'required',
            'description'=>'required',
            'price'=>'required|digits_between:5,10',
            'cost'=>'required|digits_between:5,10',
            'percent'=>'required|numeric',
            'cpu'=>'required|string|max:32',
            'hard_drive'=>'required|string|max:255',
            'mux_switch'=>'required|string|max:255',
            'screen'=>'required|string|max:255',
            'webcam'=>'required|string|max:255',
            'connection'=>'required|string|max:255',
            'weight'=>'required|string|max:255',
            'pin'=>'required|string|max:255',
            'operation_system'=>'required|string|max:255',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $err = (new ValidationValidationException($validator))->errors();
        throw new HttpResponseException(new JsonResponse($err,422));
    }
}
