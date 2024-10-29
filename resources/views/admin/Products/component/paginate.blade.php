<div class="text-left">
    {{ $products->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>
