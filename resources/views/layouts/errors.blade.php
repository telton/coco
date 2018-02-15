<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Error | {{ config('app.name') }}: {{ env('APP_DESCRIPTION') }}</title>
        <style type="text/css">
            * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }

            *:before,
            *:after {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }

            html {
                height: 100%;
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }

            a {
                color: #18222C;
                background-color: transparent;
                text-decoration: underline;
            }

            a:active,
            a:hover {
                outline: 0;
                color: #2A343E;
                text-decoration: none;
            }

            body {
                background-color: #F5F5F5;
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                display: table;
                font-family: HelveticaNeue, "Helvetica Neue", "Segoe UI", "Avenir Next", Avenir, "Gill Sans MT", Helvetica, Arial, Verdana, sans-serif;
                font-size: 16px;
                line-height: 1.42857143;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
                height: 100%;
            }

            .content {
                color: #2A343E;
                padding: 10px;
            }

            .ccblock {
                margin: auto auto;
                display: block;
                width: 110px;
                height: 110px;
                //background-color: #04132A;
                //background-image:
                background-repeat: no-repeat;
                //-webkit-box-shadow: 0 1px 10px rgba(0, 0, 0, 0.5);
                //box-shadow: 0 1px 10px rgba(0, 0, 0, 0.5);
            }

            .title {
                font-size: 60px;
                margin: 10px;
            }

            p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="ccblock"></div>
            <h1 class="title">@yield('title')</h1>
            @yield('content')
        </div>
    </body>
</html>