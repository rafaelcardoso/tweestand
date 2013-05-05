<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
    	<meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <title>{{ $title }}</title>
        <meta name="description" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="{{ URL::base() }}/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="{{ URL::base() }}/css/main.css"/>
        
        <!--[if IE 7]>
            <link rel="stylesheet" href="{{ URL::base() }}/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <script src="{{ URL::base() }}/js/libs/head.min.js"></script>

        <!--[if lt IE 9]>
            <script src="{{ URL::base() }}/js/libs/respond.min.js"></script>
        <![endif]-->
        
    </head>
	<body>
        <!--[if lt IE 9]>
        <div class="container center">
            <style>body{padding-top: 50px}</style>
            <p class="chromeframe">You are using an <strong class="label label-important">outdated</strong> browser. Please <a id="upgrade" class="btn btn-info btn-mini" target="_blank" href="http://browsehappy.com" data-placement="right" data-original-title="this will be awsome">upgrade your browser</a></p>
        </div>
        <script>head.ready("bootstrap", function(){$('#upgrade').tooltip();});</script>
        <![endif]-->
    <div id="wrap">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="{{ URL::base() }}">Tweestand</a>
                    <nav class="nav-collapse collapse">
                        <nav>
                            <ul class="nav">
                                <li><a href="{{ URL::to('features') }}">Features</a></li>
                                <li><a href="{{ URL::to('donate') }}">Donate</a></li>
                                <li><a href="{{ URL::to('contact') }}">Contact</a></li>
                            </ul>
                        </nav>
                        <ul class="nav pull-right">
                            <li class="divider-vertical"></li>
                            
                            @if(Auth::check())

                                <?$user = Session::get('myauth_login')?>
                                <li><a href="{{ URL::to('dashboard') }}"><i class="icon-th-large icon-white"></i>Dashboard</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i>{{ ucwords(explode(' ',trim($user['name']))[0]) }}<span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ URL::to('edit-profile') }}"><i class="icon-pencil"></i>edit user profile</a></li>
                                        <li><a href="{{ URL::to('manage-twitter-accounts') }}"><i class="icon-wrench"></i>manage twitter accounts</a></li>
                                        <li class="divider"></li>
                                        <li><a href="{{ URL::to('logout') }}"><i class="icon-off"></i>log out</a></li>
                                    </ul>
                                </li>

                            @else

                                <li><a href="{{ URL::to('register') }}"><i class="icon-plus-sign-alt icon-white"></i>Sign Up</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-lock icon-white"></i>Sign In <span class="caret"></span></a>
                                    <div class="dropdown-menu dropdown-login">
                                        <form action="{{ URL::to('login') }}" method="post" accept-charset="UTF-8">
                                            <div class="row-fluid help-block">
                                                <input class="span12" id="user_name" name="username" size="30" type="text" placeholder="user name" />
                                            </div> 
                                            <div class="row-fluid help-block">
                                                <input class="span12" id="user_password" name="password" size="30" type="password" placeholder="password" />
                                           </div>
                                            <div class="help-block">
                                                <input class="btn btn-info btn-block" type="submit" value="Sign In" />
                                            </div>
                                            <div>
                                                <span class="help-block">
                                                    <i class="icon-question-sign"></i>
                                                     <small><a href="{{ URL::to('forgot') }}">forgot your password?</a></small>
                                                </span>
                                                <span class="help-block">
                                                    <i class="icon-envelope"></i>
                                                    <small><a href="{{ URL::to('forgot') }}">resend confirmation email</a></small>
                                                </span>
                                                <span class="help-block">
                                                    <i class="icon-plus-sign"></i>
                                                    <small><a href="{{ URL::to('register') }}">create an account </a></small>
                                                    &nbsp;<span class="label label-info">it's free :)</span>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                                

                            @endif
                        
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        @if(!URI::is('/'))
            <div class="page-content">
                {{ $content }}
            </div>
        @else
            {{ $content }}
        @endif
        
        <div id="space"></div>
        <div id="push"></div>
    </div>

    <div class="page-footer">
        <div class="container">
            <span class="pull-left">© 2013 Tweestand. All rights reserved.</span>
            <ul class="inline pull-right" id="social-media">
                <li>
                    <a href="http://twitter.com/tweestand" class="label label-inverse" target="_blank">Follow us on Twitter
                        <span class="btn btn-mini">
                            <i class="icon-twitter blue"></i> @tweestand
                        </span>
                    </a>
                </li>
                <li>
                    <a href="http://www.facebook.com/tweestand" class="label label-inverse" target="_blank">Like us on Facebook
                        <span class="btn btn-mini">
                            <i class="icon-facebook blue"></i> tweestand
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        window.baseUrl = "{{ URL::base() }}";
        head.js(
            {jquery: "{{ URL::base() }}/js/libs/jquery.1.9.0.min.js"},
            {bootstrap: "{{ URL::base() }}/js/libs/bootstrap.min.js"},
            {main: "{{ URL::base() }}/js/main.js"}
        );
    </script>
        
    </body>
</html>