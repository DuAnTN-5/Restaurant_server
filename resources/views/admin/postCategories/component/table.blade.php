<table class="table table-striped table-bordered table-hover dataTables-categories">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Ngày Tạo</th>
            <th>Trạng thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($postCategories as $postCategory)
            <tr>
                <td>{{ $postCategory->id }}</td>
                <td>{{ $postCategory->name }}</td>
                <td>{{ $postCategory->slug }}</td>
                <td>{{ $postCategory->created_at->format('d/m/Y') }}</td>
                
                <!-- Trạng thái (checkbox với Switchery) -->
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" 
                        data-id="{{ $postCategory->id }}" 
                        data-type="postCategory"
                        {{ $postCategory->status === 'active' ? 'checked' : '' }}>
                </td>
                
                <!-- Thao tác (Chỉnh sửa và xóa) -->
                <td>
                    <a href="{{ route('postCategories.edit', $postCategory->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('postCategories.destroy', $postCategory->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
