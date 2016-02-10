<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/images/favicon.png">
        <title>{{ isset($pageTitle) ? $pageTitle : 'Welcome to KarmaCircles' }}</title>
        <meta name="description" content=" {{ isset($pageDescription) ? $pageDescription : 'KarmaCircles is a platform for finding skilled people, request them for an online/phone/in-person meeting and then thank them for the time & help given by them through KarmaNote.' }}">
        @if(isset($title))
        <meta property="og:title" content="{{$title}}" />
        @endif 
        <meta property="og:type" content="profile" />
        <meta property="og:image" content="" /> 
        <meta property="og:locale" content="en_US" /> 
        <meta property="og:url" content="" />  
        <meta property="fb:app_id" content="624275234384591" />
        <meta property="og:site_name" content="Welcome to KarmaCircles"/>
         @if(isset($description))
        <meta property="og:description" content="{{$description}}" />
        @endif 
        <!-- Bootstrap -->
        {{ HTML::style('css/font.css') }}
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/custom.css') }}
        {{ HTML::style('css/media.css') }}
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
        {{ HTML::script('js/bootstrap-datetimepicker.min.js')  }}
        {{ HTML::script('js/custom.js')  }}
        {{ HTML::script('js/classie.js')  }}
	<script>var siteURL = {{ $siteURL or 'undefined' }}</script>
		<script type="text/javascript"> 
			var _gaq = _gaq || [];
						_gaq.push(['_setAccount', 'UA-57440435-1']);
						_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
    </head>
    <body>
        @yield('body')
    </body>
</html>