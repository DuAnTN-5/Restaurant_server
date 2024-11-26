
<script>
    $(document).ready(function() {

        $('.js-switch').on('change', function() {
            const id = $(this).data('id');
            const name = $(this).data('type');
            const checked = $(this).is(':checked');

            $.ajax({
                url: `/role-details/${id}/update`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                data: JSON.stringify({
                    column_name: name,
                    checked: checked,
                }),
                success: function(response) {
                    if (response.success) {
                        console.log(response.data);
                        flash.success(response.message);
                    } else {
                        console.log('Update failed');
                        flash.success(response.message);
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                    const errorMessage = error.responseJSON?.message || error
                        .responseText || 'Unknown error occurred';
                    flash.success(`Error: ${errorMessage}`);
                },
            });
        });
    });
</script>
