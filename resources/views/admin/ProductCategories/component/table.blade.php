<!-- resources/views/admin/productcategories/component/table.blade.php -->
<table class="table table-striped table-bordered table-hover dataTables-categories">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Mô Tả</th>
            <th>Thứ Tự</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    {{ $category->name }}
                    <small style="color: red; display: block; margin-top: 5px;">
                        Danh mục cha: {{ $category->parent ? $category->parent->name : 'Không có' }}
                    </small>
                </td>
                
                <td>{{ $category->description }}</td>
                <td>{{ $category->position }}</td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $category->id }}" {{ $category->status == 'active' ? 'checked' : '' }}>
                </td>
                
                <td style="text-align: center;">
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
            <th>Mô Tả</th>
            <th>Thứ Tự</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
    </tfoot>
</table>

