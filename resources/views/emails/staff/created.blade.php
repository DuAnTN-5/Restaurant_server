@component('mail::message')
# Xin chào {{ $staff->name }},

Chúng tôi rất vui mừng thông báo rằng bạn đã được thêm vào hệ thống với các thông tin sau:

- **Tên:** {{ $staff->name }}
- **Email:** {{ $staff->email }}
- **Vị trí:** {{ $staff->position }}
- **Ngày tuyển dụng:** {{ $staff->hire_date }}

Nếu bạn có bất kỳ câu hỏi nào, xin đừng ngần ngại liên hệ với chúng tôi.

Cảm ơn bạn!

@component('mail::button', ['url' => route('staff.index')])
Xem danh sách nhân viên
@endcomponent

Cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
