<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'sku' => 'required|unique:products,sku',
            'thumbnail' => 'required|mimes:jpg,jpeg,png,webp',
            'unit' => 'required|string|max:255',
            'unitValue' => 'required|string|max:255',
            'sellingPrice' => 'required|numeric',
            'purchasePrice' => 'required|numeric',
            'discount' => 'required|integer',
            'tax' => 'required|integer',
            'variations' => 'nullable|array',
            'variations.*.attributes.*.key' => 'required',
            'variations.*.attributes.*.value' => 'required',
            'variations.*.purchasePrice' => 'required|numeric',
            'variations.*.sellPrice' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'variations.*.attributes.*.key.required' => 'Variation attribute is required',
            'variations.*.attributes.*.value.required' => 'Variation attribute value is required',
            'variations.*.purchasePrice.required' => 'Variation purchase price is required',
            'variations.*.sellPrice.required' => 'Variation sell price is required',
        ];
    }
}
