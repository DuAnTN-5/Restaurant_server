<!-- Switchery CSS and JS -->
<link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery/switchery.css') }}">
<script src="{{ asset('backend/js/plugins/switchery/switchery.js') }}"></script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="backend/css/plugins/dropzone/dropzone.css" rel="stylesheet">
<script src="backend/js/plugins/dropzone/dropzone.js"></script>

<style>
    .toast-success {
        background-color: #1AB394 !important;
        /* Success color */
    }

    .toast-error {
        background-color: red !important;
        /* Error color */
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Switchery for status toggle in Post Categories
        var elems = document.querySelectorAll('.js-switch');
        elems.forEach(function(elem) {
            var switchery = new Switchery(elem, {
                color: '#1AB394'
            });

            elem.onchange = function() {
                var postCategoryId = this.getAttribute('data-id');
                var status = this.checked ? 'active' : 'inactive';
                
                // Tạo URL động cho route update-status
                // var url = `/admin/post-categories/${postCategoryId}/update-status`;

                // Update status via fetch for post categories
                fetch('{{ route('post-categories.update-status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: postCategoryId,
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
        
        // Dropzone configuration for file upload
        Dropzone.options.dropzoneForm = {
            paramName: "file",
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload.</strong><br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
        };

        $(document).ready(function(){
            var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_three = CodeMirror.fromTextArea(document.getElementById("code3"), {
                lineNumbers: true,
                matchBrackets: true
            });
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
            "timeOut": "5000", // 5 seconds
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    });
</script>
