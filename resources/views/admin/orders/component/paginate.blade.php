<div class="text-left">
    {{ $orders->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>