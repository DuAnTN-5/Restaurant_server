@extends('admin.dashboard.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Quản Lý Nhân Viên</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.index') }}">Trang Chủ</a></li>
                <li class="active"><strong>Danh Sách Nhân Viên</strong></li>
            </ol>
        </div>
    </div>
    
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <span class="text-muted small pull-right">
                            Thời gian hiện tại: <i class="fa fa-clock-o"></i> {{ now()->setTimezone('Asia/Ho_Chi_Minh')->format('h:i A - d.m.Y') }}
                        </span>
                        
                        <h2>Danh sách nhân viên</h2>
                        <p>
                            Tất cả nhân viên gồm Manager Admin và Staff.
                        </p>
                        <div class="input-group">
                            <input type="text" placeholder="Search client " class="input form-control">
                            <span class="input-group-btn">
                                    <button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
                            </span>
                        </div>
                        <div class="clients-list">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> Thông tin liên hệ</a></li>
                            {{-- <li class="active2"><a data-toggle="tab" href="#tab-2"><i class="fa fa-briefcase"></i> Companies</a></li> --}}
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="full-height-scroll">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <tbody>
                                                @foreach($staffs as $staff)
                                                    <tr>
                                                        <!-- Ảnh đại diện -->
                                                        <td class="client-avatar">
                                                            @if($staff->user && $staff->user->image)
                                                                <img alt="image" src="{{ asset($staff->user->image) }}" style="width: 50px; height: 50px;">
                                                            @else
                                                                <img alt="no-image" src="{{ asset('default-avatar.png') }}" style="width: 50px; height: 50px;">
                                                            @endif
                                                        </td>
                                                        
                                                        <!-- Tên và liên kết đến chi tiết liên hệ -->
                                                        <td>
                                                            <a data-toggle="tab" href="#contact-{{ $staff->id }}" class="client-link">
                                                                {{ $staff->user->name }}
                                                            </a>
                                                        </td>
                                    
                                                        <!-- Phòng ban (Department) -->
                                                        <td>{{ $staff->department }}</td>
                                    
                                                        <!-- Loại liên hệ: Email hoặc Số điện thoại -->
                                                        <td class="contact-type">
                                                            @if($staff->user->email)
                                                                <i class="fa fa-envelope"> </i>
                                                            @else
                                                                <i class="fa fa-phone"> </i>
                                                            @endif
                                                        </td>
                                    
                                                        <!-- Email hoặc Số điện thoại -->
                                                        <td>
                                                            @if($staff->user->email)
                                                                {{ $staff->user->email }}
                                                            @else
                                                                {{ $staff->user->phone_number }}
                                                            @endif
                                                        </td>
                                    
                                                        <!-- Trạng thái -->
                                                        <td class="client-status">
                                                            <span class="label label-{{ $staff->status == 'active' ? 'primary' : 'warning' }}">
                                                                {{ ucfirst($staff->status) }}
                                                            </span>
                                                        </td>
                            
                                                        <!-- Vai trò (Role) -->
                                                        <td>
                                                            @if ($staff->user->role == 1)
                                                                <span class="badge badge-primary">Admin</span>
                                                            @elseif ($staff->user->role == 2)
                                                                <span class="badge badge-warning">Manager</span>
                                                            @elseif ($staff->user->role == 3)
                                                                <span class="badge badge-info">Staff</span>
                                                            @else
                                                                <span class="badge badge-secondary">Unknown</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                                </div>
                            </div>
                            
                            </div>
                            
                        </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox ">

                    <div class="ibox-content">
                        <div class="tab-content">
                            <div id="contact-1" class="tab-pane active">
                                <div class="row m-b-lg">
                                    <div class="col-lg-4 text-center">
                                        <h2>Nicki Smith</h2>

                                        <div class="m-b-sm">
                                            <img alt="image" class="img-circle" src="img/a2.jpg"
                                                 style="width: 62px">
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <strong>
                                            About me
                                        </strong>

                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.
                                        </p>
                                        <button type="button" class="btn btn-primary btn-sm btn-block"><i
                                                class="fa fa-envelope"></i> Send Message
                                        </button>
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">
                                        <strong>Last activity</strong>
                                        <ul class="list-group clear-list">
                                            <?php foreach ($staffs as $staff): ?>
                                            <li class="list-group-item fist-item" id="staff-<?= $staff['id'] ?>">
                                                <span class="pull-right">
                                                    <span id="shift_start_<?= $staff['id'] ?>"><?= date('H:i', strtotime($staff['shift_start'])) ?></span> - 
                                                    <span id="shift_end_<?= $staff['id'] ?>"><?= date('H:i', strtotime($staff['shift_end'])) ?></span>
                                                </span>
                                                <span id="name_<?= $staff['id'] ?>"><?= $staff['name'] ?></span> - 
                                                <span id="position_<?= $staff['id'] ?>"><?= $staff['position'] ?></span> 
                                                (<?= $staff['department'] ?>)
                                                <br>
                                                Mô tả công việc: <span id="task_description_<?= $staff['id'] ?>"><?= $staff['task_description'] ?></span>
                                                <br>
                                                Trạng thái: <span id="status_<?= $staff['id'] ?>"><?= $staff['status'] == 'active' ? 'Đang hoạt động' : 'Ngừng hoạt động' ?></span>
                                                <br>
                                                Lương: <span id="salary_<?= $staff['id'] ?>"><?= number_format($staff['salary']) ?> VND</span>
                                                <br>
                                                Ngày bắt đầu: <span id="hire_date_<?= $staff['id'] ?>"><?= date('d-m-Y', strtotime($staff['hire_date'])) ?></span>
                                                <br>
                                                <button class="btn btn-primary" onclick="editStaff(<?= $staff['id'] ?>)">Sửa</button>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        
                                        <hr/>
                                    
                                        <!-- Nút Lưu được ẩn -->
                                        <button class="btn btn-success" id="save_button" style="display: none;" onclick="saveStaff()">Lưu</button>
                                    </div>
                                    
                                    <script>
                                        let editId = null;
                                    
                                        function editStaff(id) {
                                            editId = id;
                                            // Chuyển các phần tử sang input để chỉnh sửa
                                            const name = document.getElementById('name_' + id).innerText;
                                            const position = document.getElementById('position_' + id).innerText;
                                            const taskDescription = document.getElementById('task_description_' + id).innerText;
                                            const salary = document.getElementById('salary_' + id).innerText.replace(/[^0-9]/g, '');
                                    
                                            // Thay đổi HTML thành input fields
                                            document.getElementById('name_' + id).innerHTML = `<input type="text" id="edit_name_${id}" value="${name}">`;
                                            document.getElementById('position_' + id).innerHTML = `<input type="text" id="edit_position_${id}" value="${position}">`;
                                            document.getElementById('task_description_' + id).innerHTML = `<input type="text" id="edit_task_description_${id}" value="${taskDescription}">`;
                                            document.getElementById('salary_' + id).innerHTML = `<input type="number" id="edit_salary_${id}" value="${salary}">`;
                                    
                                            // Hiển thị nút Lưu
                                            document.getElementById('save_button').style.display = 'inline-block';
                                        }
                                    
                                        function saveStaff() {
                                            // Lấy dữ liệu đã chỉnh sửa
                                            const name = document.getElementById('edit_name_' + editId).value;
                                            const position = document.getElementById('edit_position_' + editId).value;
                                            const taskDescription = document.getElementById('edit_task_description_' + editId).value;
                                            const salary = document.getElementById('edit_salary_' + editId).value;
                                    
                                            // Gửi dữ liệu qua AJAX để lưu
                                            $.ajax({
                                                url: '/staff/' + editId,
                                                method: 'PUT',
                                                data: {
                                                    name: name,
                                                    position: position,
                                                    task_description: taskDescription,
                                                    salary: salary,
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                success: function(response) {
                                                    // Cập nhật giao diện với dữ liệu mới
                                                    document.getElementById('name_' + editId).innerText = name;
                                                    document.getElementById('position_' + editId).innerText = position;
                                                    document.getElementById('task_description_' + editId).innerText = taskDescription;
                                                    document.getElementById('salary_' + editId).innerText = new Intl.NumberFormat().format(salary) + ' VND';
                                                    
                                                    // Ẩn nút Lưu
                                                    document.getElementById('save_button').style.display = 'none';
                                                },
                                                error: function() {
                                                    alert('Có lỗi xảy ra khi cập nhật nhân viên.');
                                                }
                                            });
                                        }
                                    </script>
                                    
                                </div>
                            </div>
                            <div id="contact-2" class="tab-pane">
                                <div class="row m-b-lg">
                                    <div class="col-lg-4 text-center">
                                        <h2>Edan Randall</h2>

                                        <div class="m-b-sm">
                                            <img alt="image" class="img-circle" src="img/a3.jpg"
                                                 style="width: 62px">
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <strong>
                                            About me
                                        </strong>

                                        <p>
                                            Many desktop publishing packages and web page editors now use Lorem Ipsum as their default tempor incididunt model text.
                                        </p>
                                        <button type="button" class="btn btn-primary btn-sm btn-block"><i
                                                class="fa fa-envelope"></i> Send Message
                                        </button>
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">

                                        <strong>Last activity</strong>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"> 09:00 pm </span>
                                                Lorem Ipsum available
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 10:16 am </span>
                                                Latin words, combined
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 08:22 pm </span>
                                                Open new shop
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 11:06 pm </span>
                                                The generated Lorem Ipsum
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 12:00 am </span>
                                                Content here, content here
                                            </li>
                                        </ul>
                                        <strong>Notes</strong>
                                        <p>
                                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words.
                                        </p>
                                        <hr/>
                                         
                                    </div>
                                </div>
                            </div>
                            <div id="contact-3" class="tab-pane">
                                <div class="row m-b-lg">
                                    <div class="col-lg-4 text-center">
                                        <h2>Jasper Carson</h2>

                                        <div class="m-b-sm">
                                            <img alt="image" class="img-circle" src="img/a4.jpg"
                                                 style="width: 62px">
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <strong>
                                            About me
                                        </strong>

                                        <p>
                                            Latin professor at Hampden-Sydney College in Virginia, looked  embarrassing hidden in the middle.
                                        </p>
                                        <button type="button" class="btn btn-primary btn-sm btn-block"><i
                                                class="fa fa-envelope"></i> Send Message
                                        </button>
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">

                                        <strong>Last activity</strong>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"> 09:00 pm </span>
                                                Aldus PageMaker including
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 10:16 am </span>
                                                Finibus Bonorum et Malorum
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 08:22 pm </span>
                                                Write a letter to Sandra
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 11:06 pm </span>
                                                Standard chunk of Lorem
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 12:00 am </span>
                                                Open new shop
                                            </li>
                                        </ul>
                                        <strong>Notes</strong>
                                        <p>
                                            Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.
                                        </p>
                                        <hr/>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div id="company-2" class="tab-pane">
                                <div class="m-b-lg">
                                    <h2>Penatibus Consulting</h2>

                                    <p>
                                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some.
                                    </p>
                                    <div>
                                        <small>Active project completion with: 22%</small>
                                        <div class="progress progress-mini">
                                            <div style="width: 22%;" class="progress-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">

                                        <strong>Last activity</strong>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"> <span class="label label-warning">WAITING</span> </span>
                                                Aldus PageMaker
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"><span class="label label-primary">NEW</span> </span>
                                                Lorem Ipsum, you need to be sure
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> <span class="label label-danger">BLOCKED</span> </span>
                                                The generated Lorem Ipsum
                                            </li>
                                        </ul>
                                        <strong>Notes</strong>
                                        <p>
                                            Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
                                        </p>
                                        <hr/>
                                        
                                    </div>
                                </div>
                            </div>
                            <div id="company-3" class="tab-pane">
                                <div class="m-b-lg">
                                    <h2>Ultrices Incorporated</h2>

                                    <p>
                                        Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.
                                    </p>
                                    <div>
                                        <small>Active project completion with: 72%</small>
                                        <div class="progress progress-mini">
                                            <div style="width: 72%;" class="progress-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">

                                        <strong>Last activity</strong>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"> <span class="label label-danger">BLOCKED</span> </span>
                                                Hidden in the middle of text
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"><span class="label label-primary">NEW</span> </span>
                                                Non-characteristic words etc.
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right">  <span class="label label-warning">WAITING</span> </span>
                                                Bonorum et Malorum
                                            </li>
                                        </ul>
                                        <strong>Notes</strong>
                                        <p>
                                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.
                                        </p>
                                        <hr/>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Mainly scripts -->
<script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('backend/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{ asset('backend/js/inspinia.js') }}"></script>
<script src="{{ asset('backend/js/plugins/pace/pace.min.js') }}"></script>

<!-- Stylesheets -->
<link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

<!-- Toastr style -->
<link href="{{ asset('backend/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

<!-- Animate and custom styles -->
<link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
