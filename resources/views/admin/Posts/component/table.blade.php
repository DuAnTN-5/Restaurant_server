<table class="table table-striped table-bordered table-hover dataTables-posts">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã Bài Viết</th>
            <th>Tiêu Đề</th>
            <th>Hình Ảnh</th>
            {{-- <th>Thứ Tự</th> --}}
            <th>Tình Trạng</th>
            <th>Ngày Tạo</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->code ?? 'Chưa có mã' }}</td>
                <td>
                    {{ $post->title }}<br>
                    <small style="color: red;">Danh mục: {{ $post->category->name ?? 'Không có' }}</small>
                </td>
                <td>
                    @if ($post->image_url && file_exists(public_path($post->image_url)))
                        <img src="{{ asset($post->image_url) }}" alt="Image" width="80">
                    @else
                        <img src="{{ asset('default-post.png') }}" alt="Default Image" width="50">
                    @endif
                </td>
                
                {{-- <td>{{ $post->position ?? 'Không xác định' }}</td> --}}
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" 
                        data-id="{{ $post->id }}" 
                        data-type="post"
                        {{ $post->status === 'active' ? 'checked' : '' }}>
                </td>
                
                
                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                <td style="text-align: center; vertical-align: middle;">
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
