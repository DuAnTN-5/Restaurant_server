<script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>

<!-- CSS Plugins -->
<link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery/switchery.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- jQuery và Các Plugin Chính -->
{{-- <script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script> --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('backend/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Switchery Plugin -->
<script src="{{ asset('backend/js/plugins/switchery/switchery.js') }}"></script>

<!-- Toastr Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Flot Chart Plugins -->
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.spline.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.symbol.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/curvedLines.js') }}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="{{ asset('backend/js/status/status.js') }}"></script>
<!-- Peity Charts -->
<script src="{{ asset('backend/js/plugins/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('backend/js/demo/peity-demo.js') }}"></script>

<!-- Custom and Plugin JavaScript -->
<script src="{{ asset('backend/js/inspinia.js') }}"></script>
<script src="{{ asset('backend/js/plugins/pace/pace.min.js') }}"></script>

<!-- jQuery UI -->
<script src="{{ asset('backend/js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Jvectormap -->
<script src="{{ asset('backend/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

<!-- Sparkline -->
<script src="{{ asset('backend/js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('backend/js/demo/sparkline-demo.js') }}"></script>

<!-- ChartJS -->
{{-- <script src="{{ asset('backend/js/plugins/chartJs/Chart.min.js') }}"></script> --}}
<!-- Status Script -->
<!-- Trong file Blade (trước khi thêm status.js) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo switchery cho tất cả các phần tử có class js-switch
        initializeSwitchery(); // Đảm bảo khởi tạo sau khi trang đã tải hoàn toàn
    });
    // Các route được khai báo trong script này
    var csrfToken = "{{ csrf_token() }}";
    var updateUserStatusRoute = "{{ route('users.update-status') }}";

    var updatePostCategoriesStatusRoute = "{{ route('postCategories.update-status') }}";

    var updatePostStatusRoute = "{{ route('posts.update-status') }}";
    var updateProductCategoriesStatusRoute = "{{ route('ProductCategories.update-status') }}";
    var updateProductStatusRoute = "{{ route('products.update-status') }}";
    var updateOrderStatusRoute = "{{ route('orders.update-status') }}";

    var updateTablesStatusRoute = "{{ route('tables.update-status') }}";
    var updateReservationsStatusRoute = "{{ route('reservations.update-status') }}";
    var updateCouponsStatusRoute = "{{ route('coupons.update-status') }}";
    var updatePaymentMethodStatusRoute = "{{ route('payment_methods.update-status') }}";
</script>
{{-- status  --}}
<script src="{{ asset('backend/js/status/status.js') }}"></script>

{{-- vẽ biểu đồ  --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {

        $('#bieudo').on('change', function() {

            thongKe($(this).val());
        });

        let myChart = null; // Biến lưu trữ đối tượng chart

        function thongKe(url) {
            $.ajax({
                url: 'http://127.0.0.1:8000' + url,
                type: 'GET',
                success: function(response) {
                    const labels = response.labels;
                    const data = response.data;

                    // Nếu biểu đồ cũ tồn tại, xóa nó
                    if (myChart) {
                        myChart.destroy();
                    }

                    // Tạo dữ liệu cho biểu đồ mới
                    const chartData = {
                        labels: labels,
                        datasets: [{
                            label: 'Tổng số tiền',
                            data: data,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    };

                    // Cấu hình cho biểu đồ mới
                    const chartOptions = {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    };

                    // Tạo biểu đồ mới
                    const ctx = document.getElementById('myChart').getContext('2d');
                    myChart = new Chart(ctx, {
                        type: 'bar', // Hoặc 'line', 'pie' tùy theo loại biểu đồ bạn muốn
                        data: chartData,
                        options: chartOptions
                    });
                },
                error: function(error) {
                    console.error('Lỗi khi lấy dữ liệu thống kê', error);
                }
            });
        }


        thongKe('/admin/thongketheongay');


    });
</script>
