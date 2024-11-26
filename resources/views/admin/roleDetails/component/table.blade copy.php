<table class="table table-striped table-bordered table-hover dataTables-roles text-center">
    <thead>
        <tr>
            <th>Tên Quyền</th>
            <th>QL Người Dùng</th>
            <th>QL Nhân Viên</th>
            <th>QL Bài Viết</th>
            <th>QL Sản phẩm</th>
            <th>QL Đặt Bàn</th>
            <th>QL Đơn Hàng</th>
            <th>QL Thanh Toán</th>
            <th>QL Khuyến Mãi</th>
            <th>QL Phản Hồi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roleDetails as $roleDetail)
            @php
                if ($roleDetail->staff->user->role == 1) {
                    $role = 'Admin';
                } elseif ($roleDetail->staff->user->role == 2) {
                    $role = 'Quản Lý';
                } elseif ($roleDetail->staff->user->role == 3) {
                    $role = 'Nhân Viên';
                }
            @endphp
            <tr>
                {{-- <td>{{ $roleDetail->id }}</td> --}}
                {{-- <td>{{ $roleDetail->staff->user->name }}</td> --}}
                <td>{{ $role }}</td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_1"
                        {{ $roleDetail->role_1 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_2"
                        {{ $roleDetail->role_2 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_3"
                        {{ $roleDetail->role_3 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_4"
                        {{ $roleDetail->role_4 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_5"
                        {{ $roleDetail->role_5 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_6"
                        {{ $roleDetail->role_6 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_7"
                        {{ $roleDetail->role_7 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_8"
                        {{ $roleDetail->role_8 ? 'checked' : '' }}>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <input type="checkbox" class="js-switch" data-id="{{ $roleDetail->id }}" data-type="role_9"
                        {{ $roleDetail->role_9 ? 'checked' : '' }}>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<style>
    .table .js-switch {
        display: inline-block;
    }

    .text-center th,
    .text-center td {
        text-align: center;
        vertical-align: middle;
    }
</style>
