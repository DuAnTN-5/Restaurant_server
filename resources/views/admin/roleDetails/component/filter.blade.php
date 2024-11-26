<div class="row">
    <div class="col-lg-12 text-right">
        <form method="POST" action="{{ route('roles.store') }}" class="form-inline" id="create-role-form">
            @csrf
            <!-- Tìm kiếm từ khóa -->
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" id="role-name" name="role_name" class="form-control" placeholder="Tên Quyền..." value="{{ request()->input('search') }}">
            </div>
            <!-- Nút Tạo Bài Viết -->
            <button type="submit" class="form-control" >Tạo Vai Trò</button>
        </form>
    </div>
</div>
