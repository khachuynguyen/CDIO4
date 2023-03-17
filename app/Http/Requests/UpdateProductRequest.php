<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends CreateProductRequest
{
    public function rules(): array
    {
        return [
            'product_name'=>'required|min:6',
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
            'quantity'=>'required|numeric'
        ];
    }
}
