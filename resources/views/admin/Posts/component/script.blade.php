<!-- Summernote CSS & JS -->
<link href="{{ asset('backend/css/plugins/summernote/summernote.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/plugins/summernote/summernote-bs3.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/plugins/summernote/summernote.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Khởi tạo Summernote
        $('.summernote').summernote();
    });

    // Hiển thị trước ảnh
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>