<x-mail::message>
# Introduction

{{$code}} is your one-time password to activate your email. It will expires in {{$expiry_time}} minutes <br><br>
You can copy and paste or enter the code when prompted in the app.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
