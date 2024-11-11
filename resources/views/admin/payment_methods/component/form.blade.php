<form method="POST" action="{{ isset($paymentMethod) ? route('payment_methods.update', $paymentMethod->id) : route('payment_methods.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($paymentMethod))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Phần bên trái - 8 cột -->
        <div class="col-lg-8">
            <!-- Tên phương thức thanh toán -->
            <div class="form-group">
                <label for="name">Tên phương thức thanh toán:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $paymentMethod->name ?? '') }}" required>
            </div>

            <!-- Partner Code -->
            <div class="form-group">
                <label for="partner_code">Partner Code:</label>
                <input type="text" name="partner_code" class="form-control" value="{{ old('partner_code', $paymentMethod->partner_code ?? '') }}">
            </div>

            <!-- Access Key -->
            <div class="form-group">
                <label for="access_key">Access Key:</label>
                <input type="text" name="access_key" class="form-control" value="{{ old('access_key', $paymentMethod->access_key ?? '') }}">
            </div>

            <!-- Secret Key -->
            <div class="form-group">
                <label for="secret_key">Secret Key:</label>
                <input type="text" name="secret_key" class="form-control" value="{{ old('secret_key', $paymentMethod->secret_key ?? '') }}">
            </div>

            <!-- Endpoint URL -->
            <div class="form-group">
                <label for="endpoint_url">Endpoint URL:</label>
                <input type="text" name="endpoint_url" class="form-control" value="{{ old('endpoint_url', $paymentMethod->endpoint_url ?? '') }}">
            </div>
        </div>

        <!-- Phần bên phải - 4 cột -->
        <div class="col-lg-4">
            <!-- Icon -->
            <div class="form-group text-center">
                <label for="icon">Chọn icon:</label>
                <div class="image-preview text-center">
                    <label for="icon">
                        <img id="preview-icon"
                            src="{{ isset($paymentMethod->icon) ? asset('icons/' . $paymentMethod->icon) : asset('logo/default-icon.jpg') }}"
                            alt="Chọn icon" width="100" style="cursor: pointer;">
                    </label>
                    <input type="file" name="icon" id="icon" style="display: none;" onchange="previewIcon(event)">
                </div>
            </div>

            <!-- Trạng thái kích hoạt -->
            <div class="form-group">
                <label for="is_active">Trạng thái:</label>
                <select name="is_active" class="form-control" required>
                    <option value="1" {{ old('is_active', $paymentMethod->is_active ?? '1') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ old('is_active', $paymentMethod->is_active ?? '') == '0' ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($paymentMethod) ? 'Cập Nhật Phương Thức' : 'Lưu Phương Thức' }}</button>
        <a href="{{ route('payment_methods.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>

<!-- Link và Script cho Tagify và Summernote -->
{{-- @include('admin.payment_methods.component.script') --}}

<script>
    function previewIcon(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-icon');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
