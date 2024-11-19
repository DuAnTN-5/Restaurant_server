<div id="paymentModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thanh Toán Đơn Hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="paymentForm" method="POST" action="{{ route('payments.process') }}">
                @csrf
                <input type="hidden" name="order_id" id="order_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Phương Thức Thanh Toán</label>
                        <select name="payment_method_id" class="form-control">
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Số Tiền Thanh Toán</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác Nhận Thanh Toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPaymentModal(orderId) {
        document.getElementById('order_id').value = orderId;
        $('#paymentModal').modal('show');
    }
</script>
