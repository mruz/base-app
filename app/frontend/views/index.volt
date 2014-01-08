{# Template View | base-app | 2.0 #}
<!DOCTYPE html>
<html lang="{{ substr(i18n.lang(), 0, 2) }}">
    <head>
        <meta charset="utf-8">
        {{ getTitle() }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{ site_desc }}">

        {{ stylesheetLink('css/bootstrap.min.css') }}
        {{ assets.outputCss() }}

        <!-- Fav and touch icons -->
        <link rel="icon" type="image/x-icon" href="{{ url.getStatic('favicon.ico') }}">
    </head>
    <body>
        <div id="wrap">
            <header class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                        {{ linkTo([NULL, config.app.name, 'class' : 'navbar-brand']) }}
                    </div>
                    <div class="collapse navbar-collapse" id="header-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active">{{ linkTo(NULL, '<span class="glyphicon glyphicon-home"></span> ' ~ __('Home')) }}</li>
                        </ul>
                        {% if ! auth.logged_in() %}
                            {{ form('user/signin', 'class' : 'navbar-form form-inline pull-right') }}
                            <div class="form-group">{{ textField([ 'username', 'class' : 'form-control', 'placeholder' : __('Username') ]) }}</div>
                            <div class="form-group">{{ passwordField([ 'password', 'class' : 'form-control', 'placeholder' : __('Password') ]) }}</div>
                            <div class="checkbox"><label><input type="checkbox"> {{ __('Remember me') }}</label></div>
                            <button type="submit" name="submit_signin" class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span> {{ __('Sign in') }}</button>
                            {{ endForm() }}
                        {% else %}
                            <ul class="nav navbar-nav pull-right pull-none">
                                <li class="dropdown">
                                    {{ linkTo([ '#', 'class' : 'dropdown-togle', 'data-toggle' : 'dropdown', auth.get_user().username ~ '<b class="caret"></b>' ]) }}
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-header">{{ auth.get_user().email }}</li>
                                        <li>{{ linkTo('user', '<span class="glyphicon glyphicon-user"></span> ' ~ __('Account')) }}</li>
                                        {% if auth.logged_in('admin') %}
                                            <li>{{ linkTo('admin', '<span class="glyphicon glyphicon-wrench"></span> ' ~ __('Admin panel')) }}</li>
                                        {% endif %}
                                        <li class="divider"></li>
                                        <li>{{ linkTo('user/signout', '<span class="glyphicon glyphicon-log-out"></span> ' ~ __('Sign out')) }}</li>
                                    </ul>
                                </li>
                            </ul>
                        {% endif%}
                    </div>
                </div>
            </header>

            <div class="container">
                {{ content() }}
            </div>
        </div>
        <footer class="navbar navbar-default navbar-fixed-bottom">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#footer-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    <p class="navbar-text">
                        {{ linkTo(NULL, config.app.name) }} &copy; {{ date('Y') }}
                    </p>
                </div>
                <div class="collapse navbar-collapse" id="footer-collapse">
                    <ul class="nav navbar-nav pull-left pull-none">
                        <li class="disabled"><span class="navbar-text">Phalcon {{ version() }}</span></li>
                        <li>{{ linkTo('user/signup', __('Sign up')) }}</li>
                    </ul>
                    <ul class="nav navbar-nav pull-right pull-none">
                        <li class="dropdown">
                            <ul class="dropdown-menu">
                                <li>{{ linkTo('lang/set/en-gb', __('English')) }}</li>
                                <li>{{ linkTo('lang/set/pl-pl', __('Polish')) }}</li>
                            </ul>
                            {{ linkTo([ '#', 'class' : 'dropdown-togle', 'data-toggle' : 'dropdown', __('Language') ~ '<b class="caret"></b>' ]) }}
                        </li>
                    </ul>
                </div>
            </div>
        </footer>

        {{ javascriptInclude('js/jquery.min.js') }}
        {{ javascriptInclude('js/bootstrap.min.js') }}

        <!-- Enable responsive features in IE8 -->
        <!--[if lt IE 9]>
        {{ javascriptInclude('js/respond.js') }}
        <![endif]-->
        {% for script in scripts %}
            <script type="text/javascript">{{ script }}</script>
        {% endfor %}
        {{ assets.outputJs() }}
    </body>
</html>