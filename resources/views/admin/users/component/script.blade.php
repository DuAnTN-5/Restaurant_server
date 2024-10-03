<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-status-toggle').forEach(function(button) {
            button.addEventListener('click', function() {
                var userId = this.getAttribute('data-id');
                var button = this;

                // Gửi yêu cầu AJAX để cập nhật trạng thái người dùng
                fetch(`{{ route('users.update-status') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: userId,
                        status: button.innerText.trim() === 'Active' ? 'inactive' : 'active' // Đổi trạng thái
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Cập nhật lại nút trạng thái nếu thành công
                    if (data.success) {
                        if (data.status === 'active') {
                            button.innerHTML = '<span class="badge badge-success">Active</span>';
                        } else {
                            button.innerHTML = '<span class="badge badge-danger">Inactive</span>';
                        }
                        // Thông báo thành công (có thể thêm phần thông báo nếu cần)
                        console.log('Trạng thái đã được cập nhật thành công.');
                    } else {
                        // Xử lý lỗi nếu có
                        console.error('Lỗi:', data.message);
                        alert('Cập nhật trạng thái thất bại. Vui lòng thử lại.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
                });
            });
        });
    });
</script>