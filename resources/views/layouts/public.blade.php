<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/public.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery-2.2.2.min.js"></script>
</head>
<body>

<div style="min-width: 980px;">
    @yield('content')
</div>

<script type="text/javascript">
    $(function() { $('.focus').focus(); });
</script>

</body>
</html>
