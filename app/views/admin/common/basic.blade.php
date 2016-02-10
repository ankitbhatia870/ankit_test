<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
        <link rel="shortcut icon" href="/images/favicon.png">
        <title>Welcome to KarmaCircles</title>
        <!-- Bootstrap -->
        {{ HTML::style('css/font.css') }}
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/custom.css') }}
        {{ HTML::style('css/media.css') }}
        {{ HTML::style('css/jquery.fancybox.css') }}
        {{ HTML::style('css/bootstrap-datetimepicker.min.css') }}
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        {{ HTML::script('js/jquery.min.js')  }}
        {{ HTML::script('js/bootstrap.min.js')  }}
        {{ HTML::script('js/moment.min.js')  }}
        {{ HTML::script('js/jquery.fancybox.js')  }}
        {{ HTML::script('js/bootstrap-datetimepicker.min.js')  }}
        {{ HTML::script('js/custom.js')  }}

    </head>
    <body>
        
        @yield('body')
    </body>
</html>