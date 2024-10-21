(function($) {
    "use strict";

    var HT = {};

    // Xử lý khi chọn Tỉnh/Thành phố, Quận/Huyện
    HT.getLocation = () => {
        $(document).on('change', '.location', function() {
            let _this = $(this);
            let target = _this.data('target');
            let location_id = _this.val();

            if (location_id !== '') {
                let option = {
                    'data': {
                        'location_id': location_id
                    },
                    'target': target
                };
                HT.sendDataTogetLocation(option);
            } else {
                $('.' + target).html('<option value="">[Chọn]</option>'); // Xóa các lựa chọn nếu không có giá trị
            }
        });
    };

    // Hàm gửi dữ liệu AJAX để lấy danh sách địa phương
    HT.sendDataTogetLocation = (option) => {
        let url = '';

        // Kiểm tra mục tiêu là quận/huyện hay phường/xã
        if (option.target === 'districts') {
            url = '/ajax/get-districts';  // URL lấy danh sách quận/huyện
        } else if (option.target === 'wards') {
            url = '/ajax/get-wards';  // URL lấy danh sách phường/xã
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: option.data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('.' + option.target).html(res.html);

                    // Nếu có district_id hoặc ward_id thì gán giá trị lại (cho mục đích chỉnh sửa)
                    if (option.target === 'districts' && typeof district_id !== 'undefined' && district_id !== '') {
                        $('.districts').val(district_id).trigger('change');
                    }

                    if (option.target === 'wards' && typeof ward_id !== 'undefined' && ward_id !== '') {
                        $('.wards').val(ward_id).trigger('change');
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Lỗi: ' + textStatus + ' ' + errorThrown);
            }
        });
    };

    // Load lại thành phố (cho trường hợp chỉnh sửa)
    HT.loadCity = () => {
        if (typeof province_id !== 'undefined' && province_id !== '') {
            $(".province").val(province_id).trigger('change');
        }
    };

    // Khởi chạy các hàm
    $(document).ready(function() {
        HT.getLocation();
        HT.loadCity();
    });
})(jQuery);
