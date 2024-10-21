{{-- <table class="table table-striped table-bordered table-hover dataTables-staffs">
    <thead>
        <tr>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Chức vụ</th>
            <th>Tình trạng</th> <!-- Thêm cột Tình trạng nếu cần -->
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($staffs as $staff)
            <tr>
                <td>
                    <img src="{{ $staff->user->getAvatarUrl() }}" alt="Avatar" width="50" height="50">
                </td>
                <td>{{ $staff->user->name }}</td>
                <td>{{ $staff->user->email }}</td>
                <td>{{ $staff->user->phone_number }}</td>
                <td>{{ $staff->position }}</td>
                <td>
                    <button class="btn btn-status-toggle" data-id="{{ $staff->user->id }}">
                        @if ($staff->user->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </button>
                </td>
                <td>
                    <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-success">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('staff.destroy', $staff->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table> --}}
{{-- </div> --}}
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
