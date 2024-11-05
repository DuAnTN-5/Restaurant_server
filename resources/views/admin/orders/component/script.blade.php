<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="{{ asset('backend/css/plugins/dropzone/dropzone.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/plugins/dropzone/dropzone.js') }}"></script>

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
<style>
    .status-select {
        font-weight: bold;
        padding: 5px;
        border-radius: 4px;
    }
    .status-pending {
        background-color: #f0ad4e; /* Đang chờ */
        color: white;
    }
    .status-confirmed {
        background-color: #5bc0de; /* Đã xác nhận */
        color: white;
    }
    .status-preparing {
        background-color: #5bc0de; /* Đang chuẩn bị */
        color: white;
    }
    .status-ready {
        background-color: #5cb85c; /* Sẵn sàng */
        color: white;
    }
    .status-completed {
        background-color: #337ab7; /* Hoàn thành */
        color: white;
    }
    .status-canceled {
        background-color: #d9534f; /* Hủy */
        color: white;
    }
</style>
<script>
    function changeStatusColor(selectElement) {
        // Loại bỏ tất cả các class màu hiện tại
        selectElement.classList.remove('status-pending', 'status-confirmed', 'status-preparing', 'status-ready', 'status-completed', 'status-canceled');
        
        // Thêm class màu tương ứng với giá trị trạng thái đã chọn
        const selectedValue = selectElement.value;
        selectElement.classList.add('status-' + selectedValue);
    }

    // Khởi tạo màu nền đúng cho các dropdown khi trang được tải
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-select').forEach(function(select) {
            changeStatusColor(select);
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Chọn tất cả dropdown trạng thái
        var statusSelects = document.querySelectorAll('.status-select');

        statusSelects.forEach(function(select) {
            select.onchange = function() {
                var orderId = this.getAttribute('data-id');
                var status = this.value;  // Lấy giá trị đã chọn từ dropdown

                // Cập nhật trạng thái qua fetch
                fetch('{{ route('orders.updateStatus') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: orderId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                    } else {
                        toastr.error('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Có lỗi xảy ra. Vui lòng thử lại.');
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
