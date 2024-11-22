<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- Các trường của User -->
    <input type="text" name="name" value="{{ $user->name }}" placeholder="Tên người dùng" />
    <input type="email" name="email" value="{{ $user->email }}" placeholder="Email" />

    <!-- Các trường bổ sung nếu vai trò là Manager hoặc Staff -->
    @if(in_array($user->role, [2, 3])) <!-- Giả sử 2 là Manager và 3 là Staff -->
        <input type="text" name="position" value="{{ $staff->position ?? '' }}" placeholder="Chức vụ" />
        <input type="text" name="department" value="{{ $staff->department ?? '' }}" placeholder="Phòng ban" />
        <input type="number" name="salary" value="{{ $staff->salary ?? '' }}" placeholder="Lương" />
    @endif

    <button type="submit">Lưu</button>
</form>
