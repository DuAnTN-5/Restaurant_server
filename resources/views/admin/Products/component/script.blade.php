<!-- Switchery CSS and JS -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery/switchery.css') }}">
<script src="{{ asset('backend/js/plugins/switchery/switchery.js') }}"></script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- <style>
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

    #image-thumbnails img {
    padding: 0 2px 0 2px;  
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Switchery for status toggle in Products
        var elems = document.querySelectorAll('.js-switch');
        elems.forEach(function(elem) {
            var switchery = new Switchery(elem, {
                color: '#1AB394'
            });

            elem.onchange = function() {
                var productId = this.getAttribute('data-id');
                var status = this.checked ? 'active' : 'inactive';

                // Update status via fetch for products
                fetch('{{ route('products.update-status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: productId,
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
</script> --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.9.8/tagify.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.9.8/tagify.min.js"></script>

<script src="{{ asset('backend/js/plugins/summernote/summernote.min.js') }}"></script>
<link href="{{ asset('backend/css/plugins/summernote/summernote.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/plugins/summernote/summernote-bs3.css') }}" rel="stylesheet">

<script>
    // Khởi tạo Summernote
    $(document).ready(function() {
        $('#description').summernote({
            height: 200, // Chiều cao của Mô tả
            minHeight: 200,
            maxHeight: 500
        });

        $('#summary').summernote({
            height: 100, // Chiều cao của Tóm tắt
            minHeight: 100,
            maxHeight: 300
        });
    });

    // Khởi tạo Tagify cho Nguyên liệu và Tags
    var ingredientsInput = document.querySelector('#ingredients');
    new Tagify(ingredientsInput, {
        delimiters: ",", 
        placeholder: "Nhập nguyên liệu và nhấn Enter"
    });

    var tagsInput = document.querySelector('#tags');
    new Tagify(tagsInput, {
        delimiters: ",",
        placeholder: "Nhập tags và nhấn Enter"
    });

    // Định dạng tiền tệ
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        value = new Intl.NumberFormat('vi-VN').format(value);
        input.value = value;
    }

    // Xem trước ảnh
    // function previewImage(event) {
    //     const file = event.target.files[0];
    //     const preview = document.getElementById('preview-image');

    //     const reader = new FileReader();
    //     reader.onload = function(e) {
    //         preview.src = e.target.result;
    //     };

    //     if (file) {
    //         reader.readAsDataURL(file);
    //     }
    // }

    function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('image-thumbnails');
    const previewImage = document.getElementById('preview-image');
    previewContainer.innerHTML = ''; // Clear current thumbnails

    if (files.length > 0) {
        const maxImages = 3;
        const fileCount = Math.min(files.length, maxImages);

        // Set default "no image" if no files are selected
        previewImage.src = URL.createObjectURL(files[0]); // Hiển thị ảnh đầu tiên được chọn

        for (let i = 0; i < fileCount; i++) {
            const file = files[i];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Tạo phần tử <img> cho thumbnail
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = 100; // Chiều rộng thumbnail
                    img.classList.add('mr-2', 'mb-2');
                    
                    // Thêm thumbnail vào container
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file); // Đọc file và chuyển đổi thành URL để hiển thị
            }
        }
    }
}



</script>