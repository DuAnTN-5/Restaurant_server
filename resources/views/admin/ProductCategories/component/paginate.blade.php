<!-- resources/views/admin/productcategories/component/paginate.blade.php -->
<div class="text-left">
    {{ $categories->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>
