<!-- Phân trang -->
<div class="d-flex justify-content-center">
    {{ $reservations->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>