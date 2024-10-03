<!-- resources/views/admin/productcategories/component/table.blade.php -->
<table class="table table-striped table-bordered table-hover dataTables-categories">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Danh mục cha (Parent)</th>
            <th>Hình ảnh</th> <!-- Cột Hình ảnh -->
            <th>Thứ Tự</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->parent ? $category->parent->name : 'Không có' }}</td>
                <td>
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="50" height="50">
                    @else
                        N/A
                    @endif
                </td> <!-- Hiển thị hình ảnh nếu có -->
                <td>{{ $category->position }}</td>
                <td>
                    <button class="btn btn-status-toggle" data-id="{{ $category->id }}" data-status="{{ $category->status }}">
                        @if ($category->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </button>
                </td>
                <td>{{ $category->created_at }}</td>
                <td>
                    <a href="{{ route('product-categories.edit', $category->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('product-categories.destroy', $category->id) }}" style="display: inline;">
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
            <th>Slug</th>
            <th>Danh mục cha (Parent)</th>
            <th>Hình ảnh</th>
            <th>Thứ Tự</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Thao Tác</th>
        </tr>
    </tfoot>
</table>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.btn-status-toggle', function() {
        var categoryId = $(this).data('id');
        var button = $(this);

        $.ajax({
            url: '{{ url('product-categories/toggle-status') }}/' + categoryId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'active') {
                    button.find('span').removeClass('badge-danger').addClass('badge-success').text('Active');
                } else {
                    button.find('span').removeClass('badge-success').addClass('badge-danger').text('Inactive');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });
</script>
