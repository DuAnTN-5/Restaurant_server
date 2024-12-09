<script src=" {{ asset('backend/js/plugins/summernote/summernote.min.js') }}"></script>
<link href="{{ asset('backend/css/plugins/summernote/summernote.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/plugins/summernote/summernote-bs3.css') }}" rel="stylesheet">
<script>
    $(document).ready(function() {

        $('.summernote').summernote();

    });
</script>
