<div class="text-left">
    {{ $categories->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>