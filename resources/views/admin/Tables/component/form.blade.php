<form method="POST" action="{{ isset($table) ? route('tables.update', $table->id) : route('tables.store') }}">
    @csrf
    @if (isset($table))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Left Section - 8 Columns -->
        <div class="col-lg-8">
            <!-- Table Number -->
            <div class="form-group">
                <label for="number">Số Bàn:</label>
                <input type="text" name="number" class="form-control" value="{{ old('number', $table->number ?? '') }}" required>
            </div>

            <!-- Seats -->
            <div class="form-group">
                <label for="seats">Số Chỗ Ngồi:</label>
                <input type="number" name="seats" class="form-control" value="{{ old('seats', $table->seats ?? '') }}" required>
            </div>

            <!-- Special Features -->
            <div class="form-group">
                <label for="special_features">Tính Năng Đặc Biệt:</label>
                <input type="text" id="special_features" name="special_features" class="form-control" 
                       placeholder="Nhập tính năng đặc biệt..." 
                       value="{{ old('special_features', $table->special_features ?? '') }}">
            </div>

            <!-- Custom Availability -->
            <div class="form-group">
                <label for="custom_availability">Tính Khả Dụng Tùy Chỉnh:</label>
                <textarea id="custom_availability" name="custom_availability" class="form-control">{{ old('custom_availability', $table->custom_availability ?? '') }}</textarea>
            </div>
        </div>

        <!-- Right Section - 4 Columns -->
        <div class="col-lg-4">
            <!-- Location -->
            <div class="form-group">
                <label for="location">Vị Trí:</label>
                <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $table->location ?? '') }}">
            </div>

            <!-- Suitable for Events -->
            <div class="form-group">
                <label for="suitable_for_events">Phù Hợp Cho Sự Kiện:</label>
                <input type="text" id="suitable_for_events" name="suitable_for_events" class="form-control" 
                       placeholder="Nhập sự kiện phù hợp..." 
                       value="{{ old('suitable_for_events', $table->suitable_for_events ?? '') }}">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select name="status" class="form-control" required>
                    <option value="available" {{ old('status', $table->status ?? 'available') == 'available' ? 'selected' : '' }}>Trống</option>
                    <option value="reserved" {{ old('status', $table->status ?? '') == 'reserved' ? 'selected' : '' }}>Đã Đặt</option>
                    <option value="occupied" {{ old('status', $table->status ?? '') == 'occupied' ? 'selected' : '' }}>Đang Sử Dụng</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">{{ isset($table) ? 'Cập Nhật Bàn' : 'Lưu Bàn' }}</button>
        <a href="{{ route('tables.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>

<!-- Include Scripts for any Additional Form Enhancements -->
@include('admin.tables.component.script')
