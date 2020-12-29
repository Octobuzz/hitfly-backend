<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body style="width: 100%; font-family: Arial, Helvetica, sans-serif; background-color: #f6f6f6; margin: 0; padding: 0;">

<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="background-color: #fff;">
    <tbody>


@include('emails.header')
@yield('content')
@include('emails.footer')
    </tbody>
</table>

</body>
</html>
