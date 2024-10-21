<div class="text-left">
    {{ $users->appends(request()->input())->links('pagination::bootstrap-4') }}
</div>