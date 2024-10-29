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
        @foreach ($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td>{{ $category->created_at->format('d/m/Y') }}</td>
            <td style="text-align: center; vertical-align: middle;">
                <input type="checkbox" class="js-switch" data-id="{{ $category->id }}" {{ $category->status == 'active' ? 'checked' : '' }}>
            </td>
            
            <td>
                <a href="{{ route('post-categories.edit', $category->id) }}" class="btn btn-success">
                    <i class="fa fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('post-categories.destroy', $category->id) }}" style="display: inline;">
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
