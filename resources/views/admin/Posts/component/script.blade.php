<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-status-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            var postId = this.getAttribute('data-id');
            var button = this;

            console.log("Clicked on post ID: ", postId);  // Thêm log để kiểm tra

            // Gửi AJAX request để thay đổi trạng thái
            fetch(`/admin/posts/${postId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("Response status: ", data.status);  // Thêm log để kiểm tra response
                if (data.status === 'published') {
                    button.innerHTML = '<span class="badge badge-success">Đã xuất bản</span>';
                } else if (data.status === 'draft') {
                    button.innerHTML = '<span class="badge badge-warning">Nháp</span>';
                } else if (data.status === 'archived') {
                    button.innerHTML = '<span class="badge badge-secondary">Lưu trữ</span>';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});


// image 
function previewImages(event) {
    var files = event.target.files;
    var previewContainer = document.getElementById('preview-images');
    previewContainer.innerHTML = ''; // Xóa các ảnh đã hiển thị trước đó

    for (var i = 0; i < files.length; i++) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imgElement = document.createElement('img');
            imgElement.src = e.target.result;
            imgElement.width = 150; // Set kích thước của ảnh
            previewContainer.appendChild(imgElement); // Thêm ảnh mới vào container
        };
        reader.readAsDataURL(files[i]); // Đọc file ảnh
    }
}

</script>
