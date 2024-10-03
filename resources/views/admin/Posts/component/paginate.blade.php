<div class="d-flex justify-content-center">
    {{ $posts->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>
