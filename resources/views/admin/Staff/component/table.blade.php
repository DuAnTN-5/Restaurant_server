<div class="ibox-content">
    <span class="text-muted small pull-right">
        Thời gian hiện tại: <i class="fa fa-clock-o"></i>
        {{ now()->setTimezone('Asia/Ho_Chi_Minh')->format('h:i A - d.m.Y') }}
    </span>

    <h2>Danh sách nhân viên</h2>
    <p>Tất cả nhân viên gồm Manager, Admin và Staff.</p>

    @include('admin.Staff.component.filter')

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th style="vertical-align: middle; text-align:center">Tên Nhân Viên</th>
                    <th>Phòng ban</th>
                    <th>Thông tin liên hệ</th>
                    <th>Vai trò</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staffs as $index => $staff) <!-- Sử dụng cú pháp $index => $staff -->
                    <tr>
                        <td class="client-avatar">
                            @if($staff->user && $staff->user->image)
                                <img alt="image" src="{{ asset($staff->user->image) }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                            @else
                                <img alt="no-image" src="{{ asset('default-avatar.png') }}" class="img-thumbnail" style="width: 50px; height: 50px;">
                            @endif
                        </td>
                        
                        
                        

                        <td style="vertical-align: middle;">
                            <a data-toggle="tab" href="#contact-{{ $staff->id }}"
                                class="client-link">{{ $staff->user->name }}</a>
                        </td>

                        <td style="vertical-align: middle;">{{ $staff->department }}</td>

                        <td style="vertical-align: middle;">
                            @if($staff->user)
                                Email: {{ $staff->user->email ?? 'Không có thông tin' }}<br>
                                SĐT: {{ $staff->user->phone_number ?? 'Không có thông tin' }}
                            @else
                                <span>Không có người dùng liên kết</span>
                            @endif
                        </td>

                        <td style="text-align: center; vertical-align: middle;">
                            @php
                                $roles = ['1' => 'Admin', '2' => 'Manager', '3' => 'Staff'];
                            @endphp

                            @if($staff->user)
                                <span class="badge badge-{{ $staff->user->role == 1 ? 'primary' : ($staff->user->role == 2 ? 'warning' : 'info') }}">
                                    {{ $roles[$staff->user->role] ?? 'Unknown' }}
                                </span>
                            @else
                                <span class="badge badge-secondary">No user</span>
                            @endif
                        </td>

                        <td style="text-align: center; vertical-align: middle;">
                            <!-- Nút chỉnh sửa cho user liên kết với staff -->
                            @if($staff->user)
                                <a href="{{ route('users.edit', $staff->user->id) }}" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                            @else
                                <span>Không có user</span>
                            @endif

                            <!-- Form xóa cho user liên kết với staff -->
                            @if($staff->user)
                                <form method="POST" action="{{ route('users.destroy', $staff->user->id) }}"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function performSearch() {
        const query = document.getElementById('searchInput').value;
        window.location.href = `{{ route('staff.index') }}?search=${query}`;
    }
</script>
