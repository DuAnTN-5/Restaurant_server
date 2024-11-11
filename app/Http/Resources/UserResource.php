<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // Tên người dùng
            'email' => $this->email, // Email người dùng
            'created_at' => $this->created_at, // Ngày tạo tài khoản
            'updated_at' => $this->updated_at, // Ngày cập nhật thông tin
            'is_verified' => $this->is_verified, // Trạng thái xác minh
            'verification_token' => $this->verification_token, // Mã xác nhận
            'verification_expires_at' => $this->verification_expires_at, // Thời gian hết hạn mã xác nhận
        ];
    }
}
