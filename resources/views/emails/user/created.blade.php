@component('mail::message')
# User Created

A new user has been created.

**Name:** {{ $user->name }}  
**Email:** {{ $user->email }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
