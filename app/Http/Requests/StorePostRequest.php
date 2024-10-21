<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:post_categories,id', // Chỉ định bảng post_categories
            'body' => 'nullable|string',
            'status' => 'required|string|in:published,draft,archived', // Chỉ định các trạng thái hợp lệ
            'image_url' => 'nullable|url',
        ];
    }
}
