<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Teamo Landing Page</title>

        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: Helvetica Neue, Helvetica, Arial sans-serif;
                font-weight: normal;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 32px;
                margin-bottom: 30px;
                color: #636b6f;
            }

            a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title">
                    Welcome to Teamo!
                </div>
                <a href="{{ url('/login') }}">Login</a>
                &nbsp;
                <a href="{{ url('/register') }}">Register</a>
            </div>
        </div>
    </body>
</html>
