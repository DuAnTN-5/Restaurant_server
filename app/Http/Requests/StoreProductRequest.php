<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Đặt thành true để cho phép request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'summary' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:product_categories,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock_quantity' => 'nullable|integer',
            'discount_price' => 'nullable|numeric',
            'availability' => 'nullable|boolean',
            'ingredients' => 'nullable|string',
            'position' => 'nullable|integer',
            'tags' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'product_code' => 'required|string|unique:products,product_code',
        ];
    }
}
