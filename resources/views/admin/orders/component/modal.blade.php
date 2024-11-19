<!-- Modal HTML -->
<div class="modal fade" id="orderItemModal" tabindex="-1" aria-labelledby="orderItemModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog">
        <div class="modal-content custom-modal-content">
            {{-- <form action="{{ route('orders.storeItems', 1) }}" method="post"> --}}
                {{-- @csrf
                @method('POST') --}}

                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemModalLabel">Danh Sách Món Trong Đơn Hàng</h5>
                    <p><strong>Total:</strong> <span id="cart-total">$0.00</span></p>
                    <button class="btn btn-primary w-100" type="button" onclick="saveOrderItems()">Xác Nhận</button>  {{-- onclick="saveOrderItems()"--}}
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body custom-modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="modal-content">
                                Đang tải dữ liệu...
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </form> --}}
        </div>
    </div>
</div>


<!-- Script cho modal -->
<script>
    // Hàm mở modal và load danh sách món ăn qua AJAX
    function openOrderItemModal(orderId) {
        // Lưu orderId vào modal để sử dụng khi lưu đơn hàng
        $('#orderItemModal').data('order-id', orderId);
        $('#orderItemModal').modal('show');

        // Gọi AJAX để load danh sách món ăn vào modal
        $.ajax({
            url: `/orders/${orderId}/items`, // Route để lấy danh sách món ăn cho đơn hàng
            method: 'GET',
            success: function(response) {
                $('#modal-content').html(response); // Đưa nội dung danh sách món vào modal
                updateCartTotal(); // Cập nhật tổng tiền khi dữ liệu đã được load
            },
            error: function() {
                $('#modal-content').html('Không thể tải dữ liệu.');
            }
        });
    }

    // Hàm tính tổng tiền dựa trên số lượng món ăn
    function updateCartTotal() {
        let total = 0;
        // Duyệt qua tất cả các ô nhập số lượng để tính tổng tiền
        $('.quantity-input').each(function() {
            const quantity = $(this).val();
            const price = parseFloat($(this).closest('.cart-item').find('.item-price').data('price'));
            total += price * quantity;
        });
        $('#cart-total').text(`$${total.toFixed(2)}`);
    }

    // Cập nhật tổng tiền khi thay đổi số lượng món
    $(document).on('input', '.quantity-input', function() {
        updateCartTotal();
    });

    // Hàm lưu các món đã chọn vào đơn hàng khi nhấn "Xác Nhận"
    function saveOrderItems() {
        const orderId = $('#orderItemModal').data('order-id');
        const quantities = {};

        // Lấy số lượng của từng sản phẩm trong modal
        $('.quantity-input').each(function() {
            const productId = $(this).attr('name').match(/\d+/)[0];
            quantities[productId] = $(this).val();
        });

        // Gửi AJAX để lưu các món ăn vào đơn hàng
        $.ajax({
            url: `/orders/${orderId}/items`, // Route để lưu các món ăn vào đơn hàng
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({
                quantities: quantities
            }),
            success: function(response) {
                toastr.success('Đã thêm món vào đơn hàng thành công!');
                $('#orderItemModal').modal('hide');
                location.reload(); // Tải lại trang để cập nhật
            },
            error: function() {
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại.');
            },

        });
    }

    // Khởi tạo Toastr (chỉ khi cần thông báo thành công hoặc lỗi)
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
        };
    });
</script>
