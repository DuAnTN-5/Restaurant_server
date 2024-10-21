<!-- layoutadmin.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <!-- Include the head section -->
    @include('admin.dashboard.component.head')

</head>

<body>
    <div id="wrapper">
        <!-- Include the sidebar -->
        @include('admin.dashboard.component.sidebar')

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <!-- Include the top navbar -->
                @include('admin.dashboard.component.topnav')
            </div>

            <!-- Include the right sidebar -->
            @include('admin.dashboard.component.right-sidebar')

            <!-- Main content (content.blade.php) sẽ được chèn vào đây -->
            {{-- @include('admin.dashboard.component.content') --}}
            @yield('content')
            <!-- Include the footer -->
            @include('admin.dashboard.component.footer')
        </div>

    </div>

    <!-- Include the script section -->
    @include('admin.dashboard.component.script')

    @stack('scripts')
</body>

</html>
