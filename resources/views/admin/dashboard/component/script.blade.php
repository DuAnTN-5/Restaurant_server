<!-- CSS Plugins -->
<link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery/switchery.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- jQuery và Các Plugin Chính -->
<script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>

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
<script src="{{ asset('backend/js/plugins/chartJs/Chart.min.js') }}"></script>

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
    // var updatePaymentMethodStatusRoute = "{{ route('payment_methods.update-status') }}";
    var updatePostCategoriesStatusRoute = "{{ route('postCategories.update-status') }}";

    var updatePostStatusRoute = "{{ route('posts.update-status') }}";
    var updateProductCategoriesStatusRoute = "{{ route('ProductCategories.update-status') }}";
    var updateProductStatusRoute = "{{ route('products.update-status') }}";
    var updateOrderStatusRoute = "{{ route('orders.update-status') }}";  // Thêm nếu cần
   
    var updateTablesStatusRoute = "{{ route('tables.update-status') }}";  // Thêm nếu cần
    var updateReservationsStatusRoute = "{{ route('reservations.update-status') }}";  // Thêm nếu cần
    var updateCouponsStatusRoute = "{{ route('coupons.update-status') }}";  // Thêm nếu cần
    var updatePaymentMethodStatusRoute = "{{ route('payment_methods.update-status') }}";  // Thêm nếu cần
</script>

<script src="{{ asset('backend/js/status/status.js') }}"></script>


