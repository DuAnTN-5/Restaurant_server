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
        // Initialize Switchery for status toggle in Posts
        var elems = document.querySelectorAll('.js-switch');
        elems.forEach(function(elem) {
            var switchery = new Switchery(elem, {
                color: '#1AB394'
            });

            elem.onchange = function() {
                var postId = this.getAttribute('data-id');
                var status = this.checked ? 'published' : 'draft';

                // Update status via fetch for posts
                fetch('{{ route('posts.update-status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: postId,
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
</script>
