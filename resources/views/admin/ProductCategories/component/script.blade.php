<!-- resources/views/admin/productcategories/component/script.blade.php -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-status-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var categoryId = this.getAttribute('data-id');
                var button = this;

                // Gửi yêu cầu AJAX để cập nhật trạng thái
                fetch(`/admin/categories/product-categories/${categoryId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        if (data.status === 'active') {
                            button.innerHTML = '<span class="badge badge-success">Active</span>';
                        } else {
                            button.innerHTML = '<span class="badge badge-danger">Inactive</span>';
                        }
                    } else {
                        console.error('Không có dữ liệu trạng thái trả về');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>
