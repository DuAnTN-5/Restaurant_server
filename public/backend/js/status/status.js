function initializeSwitchery() {
    var elems = document.querySelectorAll('.js-switch');
    
    elems.forEach(function(elem) {
        var switchery = new Switchery(elem, {
            color: '#1AB394'  
        });

        // Xử lý sự kiện khi thay đổi trạng thái
        elem.onchange = async function() {
            var itemId = this.getAttribute('data-id');
            var itemType = this.getAttribute('data-type');
            var checked = this.checked ? 1 : 0;
            var status = checked == 1 ? 'active' : 'inactive';
            // console.log(itemType, status, itemId);
            // Xác định route cần sử dụng dựa trên loại đối tượng (itemType)
            var updateStatusRoute;
            switch (itemType) {
                case 'user':
                    updateStatusRoute = updateUserStatusRoute;
                    break;
                case 'payment_methods':
                    updateStatusRoute = updatePaymentMethodStatusRoute;
                    break;
                case 'postCategory':
                    updateStatusRoute = updatePostCategoriesStatusRoute;
                    break;
                case 'post':
                    updateStatusRoute = updatePostStatusRoute;
                    break;
                case 'product':
                    updateStatusRoute = updateProductStatusRoute;
                    break;
                case 'productCategory':
                    updateStatusRoute = updateProductCategoriesStatusRoute;
                    break;
                case 'order':
                    updateStatusRoute = updateOrderStatusRoute;
                    break;
                case 'table':
                    updateStatusRoute = updateTablesStatusRoute;
                    break;
                case 'reservation':
                    updateStatusRoute = updateReservationsStatusRoute;
                    break;
                case 'coupons':
                    updateStatusRoute = updateCouponsStatusRoute;
                    break;
               
                default:
                    console.error('Không nhận diện được loại đối tượng.');
                    return;
            }

            try {
                const response = await fetch(updateStatusRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id: itemId,
                        status: status
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // console.log(response);

                // Kiểm tra nếu API trả về một JSON hợp lệ
                const data = await response.json(); // sai cái chổ này
                if (data.success) {
                    toastr.success(data.message);  // Hiển thị thông báo thành công
                } else {
                    toastr.error('Có lỗi xảy ra ở phía serve, vui lòng thử lại.');
                }
            } catch (error) {

                console.error('Error:', error);
                toastr.error('Có lỗi xảy ra. Vui lòng thử lại.');
            }
            
        };
    });
}
