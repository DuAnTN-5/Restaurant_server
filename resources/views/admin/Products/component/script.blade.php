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