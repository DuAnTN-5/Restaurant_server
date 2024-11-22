<div class="ibox-content">
    <div class="tab-content">
        @foreach ($staffs as $staff)
            <div id="contact-{{ $staff->id }}" class="tab-pane">
                <div class="row m-b-lg">
                    <div class="col-lg-4 text-center">
                        <div class="m-b-sm">
                            <img alt="image" class="img-circle" src="{{ asset($staff->user->image) ?? asset('default-avatar.png') }}" style="width: 80px; height:80px ">
                        </div>

                    </div>
                    <div class="col-lg-8">
                        <strong>{{ $staff->user->name }}</strong>
                        <br>
                        <td>Phòng ban: {{ $staff->department  }}</td>

                        <button type="button" class="btn btn-primary btn-sm btn-block" onclick="window.location.href='mailto:someone@example.com?subject=Subject&body=Body';">
                            <i class="fa fa-envelope"></i> Send Message
                        </button>
                        
                    </div>
                </div>
                <div class="client-detail">
                    <div class="full-height-scroll">
                        <strong style="color:red">Thông tin</strong>
                        <ul class="list-group clear-list">
                            <li class="list-group-item">

                                <strong>Tên nhân viên: {{ $staff->user->name }}</strong> <br>
                                <strong>Vị trí:  {{ $staff->department }}</strong>
                            </li>
                            <li class="list-group-item">
                                <strong>Mức Lương:</strong> {{ number_format($staff->salary, 0, ',', '.') }} VND
                            </li>
                
                            <li class="list-group-item">
                                <strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::createFromTimestamp($staff->hire_date)->format('d-m-Y') }}
                            </li>
                
                            <li class="list-group-item">
                                <strong>Địa chỉ:</strong> {{ $staff->user->address ?? 'Chưa có địa chỉ' }}
                            </li>
                            {{-- <span class="pull-right">
                                    <span>{{ date('H:i', strtotime($staff->shift_start)) }}</span> 
                                    <span>{{ date('H:i', strtotime($staff->shift_end)) }}</span>
                                </span> --}}
                        </ul>
                    </div>
                </div>
                
                
            </div>
        @endforeach
    </div>
</div>
