<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="locale" content="{{ \App::getLocale() }}">
    <meta name="week-start" content="{{ $userFirstDayOfWeek }}">
    <meta name="date-format" content="{{ $userDateFormat }}">
    <meta name="scroll-to" content="{{ isset($scrollTo) ? $scrollTo : '' }}">

    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}" />

    <title>@yield('title')</title>

    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">

    <link href="/lightbox/css/lightbox.css" rel="stylesheet">

    <link href="/css/app.css?v1" rel="stylesheet">
    <link href="/css/app2.css?v14" rel="stylesheet">
    <link href="/css/helpers.css" rel="stylesheet">

    <script type="text/javascript" src="/js/jquery-2.2.2.min.js"></script>
    <script type="text/javascript" src="/js/lang/{{ \App::getLocale() }}.js?v1"></script>
    <script type="text/javascript" src="/js/common.js?v2"></script>

    @section('scripts') @show

    <script src="/bootstrap/js/bootstrap.min.js"></script>

</head>

<body>


<header>
    <div class="header">
        <div class="header-my-projects">
            <a href="/my" class="system">{{ trans('app.my_projects') }}</a>
        </div>
        <a href="/my" class="header-logo">
            teamo
        </a>
        <nav>
            <ul class="header-profile">
                <li>{!! Html::linkRoute('user.profile.profile', $userName, [], ['class' => 'system']) !!}</li>
                <li class="nav-button"><a href="{{ url('/logout') }}" class="system">{{ trans('profile.logout') }}</a></li>
            </ul>
        </nav>
    </div>
</header>

<div>

    <div class="container-header">
        <h1>
            @section('header')
            @if (isset($selectedProjectName))
                {{ Html::linkRoute('project.project.show', $selectedProjectName, $selectedProjectId, ['style' => 'color:inherit;font-weight: 500;']) }}
            @endif
            @show
        </h1>
    </div>

    <div class="container-content">
        @yield('content')
    </div>

</div>

</body>
</html>
