<!-- resources/views/admin/productcategories/component/script.blade.php -->
<!-- Switchery CSS and JS -->
<link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery/switchery.css') }}">
<script src="{{ asset('backend/js/plugins/switchery/switchery.js') }}"></script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<style>
    .toast-success {
        background-color: #1AB394 !important;
    }

    .toast-error {
        background-color: red !important;
    }

    .text-center th,
    .text-center td {
        text-align: center;
        vertical-align: middle;
    }

    .img-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Switchery for status toggle
        var elems = document.querySelectorAll('.js-switch');
        elems.forEach(function(elem) {
            var switchery = new Switchery(elem, {
                color: '#1AB394'
            });

            elem.onchange = function() {
                var categoryId = this.getAttribute('data-id');
                var status = this.checked ? 'active' : 'inactive';

                // Update status via fetch for Product Categories
                fetch('{{ route('product-categories.update-status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: categoryId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error('Đã xảy ra lỗi, vui lòng thử lại.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Đã xảy ra lỗi. Vui lòng thử lại.');
                    });
            };
        });

        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    });
</script>
{{-- <script>
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
</script> --}}
