<form method="POST" action="{{ isset($staff) ? route('staff.update', $staff->id) : route('staff.store') }}" enctype="multipart/form-data">
    @csrf
    @if (isset($staff))
        @method('PUT')
    @endif

    <!-- Thông Tin Chung -->
    {{-- <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">
                    <h3>Thông Tin Chung</h3>
                </div>
                <div class="panel-description">
                    <p>Chỉnh sửa thông tin của nhân viên</p>
                    <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                    <!-- Name and Position Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Họ Tên <span class="text-danger">(*)</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $staff->name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="position">Chức Vụ <span class="text-danger">(*)</span></label>
                                <input type="text" name="position" id="position" class="form-control" value="{{ old('position', $staff->position ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Hire Date and Department Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="hire_date">Ngày Tuyển Dụng <span class="text-danger">(*)</span></label>
                                <input type="date" name="hire_date" id="hire_date" class="form-control" value="{{ old('hire_date', $staff->hire_date ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="department">Phòng Ban <span class="text-danger">(*)</span></label>
                                <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $staff->department ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Salary and Status Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="salary">Lương <span class="text-danger">(*)</span></label>
                                <input type="number" name="salary" id="salary" class="form-control" step="0.01" value="{{ old('salary', $staff->salary ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Trạng Thái <span class="text-danger">(*)</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" {{ old('status', $staff->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $staff->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="terminated" {{ old('status', $staff->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Clients</h2>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">Home</a>
            </li>
            <li>
                <a>App Views</a>
            </li>
            <li class="active">
                <strong>Clients</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
<div class="row">
    <div class="col-sm-8">
        <div class="ibox">
            <div class="ibox-content">
                <span class="text-muted small pull-right">Last modification: <i class="fa fa-clock-o"></i> 2:10 pm - 12.06.2014</span>
                <h2>Clients</h2>
                <p>
                    All clients need to be verified before you can send email and set a project.
                </p>
                <div class="input-group">
                    <input type="text" placeholder="Search client " class="input form-control">
                    <span class="input-group-btn">
                            <button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
                    </span>
                </div>
                <div class="clients-list">
                <ul class="nav nav-tabs">
                    <span class="pull-right small text-muted">1406 Elements</span>
                    <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> Contacts</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-briefcase"></i> Companies</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="full-height-scroll">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="client-avatar"><img alt="image" src="img/a2.jpg"> </td>
                                        <td><a data-toggle="tab" href="#contact-1" class="client-link">Anthony Jackson</a></td>
                                        <td> Tellus Institute</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> gravida@rbisit.com</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><img alt="image" src="img/a3.jpg"> </td>
                                        <td><a data-toggle="tab" href="#contact-2" class="client-link">Rooney Lindsay</a></td>
                                        <td>Proin Limited</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> rooney@proin.com</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><img alt="image" src="img/a4.jpg"> </td>
                                        <td><a data-toggle="tab" href="#contact-3" class="client-link">Lionel Mcmillan</a></td>
                                        <td>Et Industries</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +432 955 908</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a5.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-4" class="client-link">Edan Randall</a></td>
                                        <td>Integer Sem Corp.</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +422 600 213</td>
                                        <td class="client-status"><span class="label label-warning">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a6.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-2" class="client-link">Jasper Carson</a></td>
                                        <td>Mone Industries</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +400 468 921</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a7.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-3" class="client-link">Reuben Pacheco</a></td>
                                        <td>Magna Associates</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> pacheco@manga.com</td>
                                        <td class="client-status"><span class="label label-info">Phoned</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a1.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-1" class="client-link">Simon Carson</a></td>
                                        <td>Erat Corp.</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> Simon@erta.com</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a3.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-2" class="client-link">Rooney Lindsay</a></td>
                                        <td>Proin Limited</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> rooney@proin.com</td>
                                        <td class="client-status"><span class="label label-warning">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a4.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-3" class="client-link">Lionel Mcmillan</a></td>
                                        <td>Et Industries</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +432 955 908</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a5.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-4" class="client-link">Edan Randall</a></td>
                                        <td>Integer Sem Corp.</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +422 600 213</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a2.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-1" class="client-link">Anthony Jackson</a></td>
                                        <td> Tellus Institute</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> gravida@rbisit.com</td>
                                        <td class="client-status"><span class="label label-danger">Deleted</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a7.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-2" class="client-link">Reuben Pacheco</a></td>
                                        <td>Magna Associates</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> pacheco@manga.com</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a5.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-3"class="client-link">Edan Randall</a></td>
                                        <td>Integer Sem Corp.</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +422 600 213</td>
                                        <td class="client-status"><span class="label label-info">Phoned</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a6.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-4" class="client-link">Jasper Carson</a></td>
                                        <td>Mone Industries</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +400 468 921</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a7.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-2" class="client-link">Reuben Pacheco</a></td>
                                        <td>Magna Associates</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> pacheco@manga.com</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a1.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-1" class="client-link">Simon Carson</a></td>
                                        <td>Erat Corp.</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> Simon@erta.com</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a3.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-3" class="client-link">Rooney Lindsay</a></td>
                                        <td>Proin Limited</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> rooney@proin.com</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a4.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-4" class="client-link">Lionel Mcmillan</a></td>
                                        <td>Et Industries</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +432 955 908</td>
                                        <td class="client-status"><span class="label label-primary">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a5.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-1" class="client-link">Edan Randall</a></td>
                                        <td>Integer Sem Corp.</td>
                                        <td class="contact-type"><i class="fa fa-phone"> </i></td>
                                        <td> +422 600 213</td>
                                        <td class="client-status"><span class="label label-info">Phoned</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a2.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-2" class="client-link">Anthony Jackson</a></td>
                                        <td> Tellus Institute</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> gravida@rbisit.com</td>
                                        <td class="client-status"><span class="label label-warning">Waiting</span></td>
                                    </tr>
                                    <tr>
                                        <td class="client-avatar"><a href=""><img alt="image" src="img/a7.jpg"></a> </td>
                                        <td><a data-toggle="tab" href="#contact-4" class="client-link">Reuben Pacheco</a></td>
                                        <td>Magna Associates</td>
                                        <td class="contact-type"><i class="fa fa-envelope"> </i></td>
                                        <td> pacheco@manga.com</td>
                                        <td class="client-status"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
    <!-- Submit Button -->
    <div class="row">
        <div class="col-lg-12 text-right">
            <button type="submit" class="btn btn-primary">{{ isset($staff) ? 'Cập nhật' : 'Thêm mới' }}</button>
        </div>
    </div>
</form>
