{# Template View | base-app | 1.2 #}
<!DOCTYPE html>
<html lang="{{ substr(i18n.lang(), 0, 2) }}">
    <head>
        <meta charset="utf-8">
        {{ getTitle() }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{ site_desc }}">

        {{ stylesheetLink('css/bootstrap.min.css') }}
        <style>
            body { padding-top: 45px; }
        </style>
        {{ stylesheetLink('css/bootstrap-responsive.min.css') }}
        {{ assets.outputCss('headerCss') }}

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        {{ javascriptInclude('js/html5shiv.js') }}
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="{{ url.getStatic('favicon.ico') }}">
    </head>
    <body>
        <header class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    {{ linkTo([NULL, config.site.name, 'class' : 'brand']) }}
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active">{{ linkTo(NULL, __('Home')) }}</li>
                        </ul>
                        {% if ! auth.logged_in() %}
                            {{ form('user/signin', 'class' : 'navbar-form pull-right') }}
                            {{ textField([ 'username', 'class' : 'span2', 'placeholder' : __('Username') ]) }}
                            {{ passwordField([ 'password', 'class' : 'span2', 'placeholder' : __('Password') ]) }}
                            {{ submitButton([ 'name' : 'submit_signin', 'class' : 'btn', __('Sign in') ]) }}
                            {{ endForm() }}
                        {% else %}
                            <ul class="nav pull-right">
                                <li class="dropdown">
                                    {{ linkTo([ '#', 'class' : 'dropdown-togle', 'data-toggle' : 'dropdown', auth.get_user().username ~ '<b class="caret"></b>' ]) }}
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">{{ auth.get_user().email }}</li>
                                        <li>{{ linkTo('user', __('Account')) }}</li>
                                        {% if auth.logged_in('admin') %}
                                            <li>{{ linkTo('admin', __('Admin panel')) }}</li>
                                        {% endif %}
                                        <li class="divider"></li>
                                        <li>{{ linkTo('user/signout', __('Sign out')) }}</li>
                                    </ul>
                                </li>
                            </ul>
                        {% endif%}
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </header>

        <div class="container">
            {{ content() }}
        </div> <!-- /container -->
        
        <footer>
            <div class="navbar navbar-toolbar navbar-fixed-bottom">
                <div class="navbar-inner">
                    <div class="container">
                        <p class="navbar-text pull-left">{{ config.site.name }} &copy; {{ date('Y') }}</p>
                        <ul class="nav">
                            <li>{{ linkTo('user/signup', __('Sign up')) }}</li>
                        </ul>
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                {{ linkTo([ '#', 'class' : 'dropdown-togle', 'data-toggle' : 'dropdown', __('Language') ~ '<b class="caret"></b>' ]) }}
                                <ul class="dropdown-menu">
                                    <li>{{ linkTo('lang/set/en-gb', __('English')) }}</li>
                                    <li>{{ linkTo('lang/set/pl-pl', __('Polish')) }}</li>
                                </ul>
                            </li>
                        </ul>
                        <p class="navbar-text pull-right">Phalcon {{ version() }}</p>
                    </div>
                </div>
            </div>
        </footer>
        {{ javascriptInclude('js/jquery.min.js') }}
        {{ javascriptInclude('js/bootstrap.min.js') }}
        {{ assets.outputJs('footerJs') }}
    </body>
</html>