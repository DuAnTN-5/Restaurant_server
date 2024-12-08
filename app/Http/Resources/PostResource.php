<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'code' => $this->code,
            'body' => $this->body,
            'summary' => $this->summary,
            'category_id' => $this->category_id, // Bao gồm thông tin category
            'user_id' => $this->user_id, // Bao gồm thông tin category
            'status' => $this->status,
            'position' => $this->position, // Thứ tự sắp xếp
            'image_url' => $this->image_url,
            // 'created_at' => $this->created_at,
            'created_at' => [
                'date' => Carbon::parse($this->created_at)->locale('vi')->isoFormat('DD/MM/YYYY'), // Ngày tạo
                'time' => Carbon::parse($this->created_at)->locale('vi')->isoFormat('HH:mm:ss'), // Giờ tạo
            ],
            'updated_at' => $this->updated_at,
        ];
    }
}
