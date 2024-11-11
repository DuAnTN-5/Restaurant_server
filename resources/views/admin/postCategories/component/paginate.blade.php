<div class="text-left">
    {{ $postCategories->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>