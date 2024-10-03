<table class="table table-striped table-bordered table-hover dataTables-categories">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Trạng thái</th>
            <th>Slug</th>
            <th>Thứ Tự</th>
            <th>Ngày Tạo</th> <!-- Thêm cột Ngày Tạo -->
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>
                <button class="btn btn-status-toggle" data-id="{{ $category->id }}">
                    @if ($category->status == 'active')
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </button>
            </td>
            <td>{{ $category->slug }}</td>
            <td>{{ $category->position }}</td>
            <td>{{ $category->created_at->format('d/m/Y') }}</td> <!-- Hiển thị ngày tạo -->
            <td>
                <a href="{{ route('PostCategories.edit', $category->id) }}" class="btn btn-success">
                    <i class="fa fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('PostCategories.destroy', $category->id) }}" style="display: inline;">
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
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Trạng thái</th>
            <th>Slug</th>
            <th>Thứ Tự</th>
            <th>Ngày Tạo</th> <!-- Thêm cột Ngày Tạo -->
            <th>Thao Tác</th>
        </tr>
    </tfoot>
</table>
