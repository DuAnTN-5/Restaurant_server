<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentPost extends Model
{
    protected $table = 'comments_post';

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    // Liên kết với người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Liên kết với bình luận cha
    public function parent()
    {
        return $this->belongsTo(CommentPost::class, 'parent_id')->where('parent_id', '!=', 0);
    }
    

    // Liên kết với các bình luận con
    public function children()
    {
        return $this->hasMany(CommentPost::class, 'parent_id')->with('children'); // Đệ quy
    }
}
