{# Template View | base-app | 2.0 #}
<!DOCTYPE html>
<html lang="{{ substr(i18n.lang(), 0, 2) }}">
    <head>
        <meta charset="utf-8">
        {{ getTitle() }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{ siteDesc }}">
        {{ stylesheetLink('css/bootstrap.min.css') }}
        {{ this.assets.outputCss() }}
        <!-- Fav and touch icons -->
        <link rel="icon" type="image/x-icon" href="{{ this.url.getStatic('favicon.ico') }}">
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Brand</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <div class="divide-nav">
            <div class="container">
                <p class="divide-text">Some Text Here</p>
            </div>
        </div>
        <nav class="navbar navbar-default navbar-lower" role="navigation">
            <div class="container">
                <div class="collapse navbar-collapse collapse-buttons">
                    <form class="navbar-form navbar-left" role="search">
                        <button class="btn btn-success">Button</button>
                        <button class="btn btn-default">Button</button>
                        <button class="btn btn-default">Button</button>
                        <button class="btn btn-default">Button</button>
                    </form>
                </div>
            </div>
        </nav>
        <div id="wrap">
            <div class="container">
                {{ content() }}
            </div>
        </div>
        <footer class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#footer-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                    <p class="navbar-text">
                        {{ linkTo(NULL, this.config.app.name) }} &copy; {{ date('Y') }}
                    </p>
                </div>
                <div class="collapse navbar-collapse" id="footer-collapse">
                    <ul class="nav navbar-nav pull-left pull-none">
                        <li class="disabled"><span class="navbar-text">Phalcon {{ version() }}</span></li>
                        <li>{{ linkTo('contact', __('Contact')) }}</li>
                        <li>{{ linkTo('user/signup', __('Sign up')) }}</li>
                    </ul>
                    <ul class="nav navbar-nav pull-right pull-none">
                        <li class="dropdown dropup">
                            <ul class="dropdown-menu">
                                {% for lang, language in siteLangs %}
                                    <li>{{ linkTo('lang/set/' ~ lang, language) }}</li>
                                    {% endfor %}
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
        {{ javascriptInclude('js/respond.min.js') }}
        <![endif]-->
        {{ this.assets.outputJs() }}
        {% if count(scripts) %}
            {% for script in scripts %}
                <script type="text/javascript">{{ script }}</script>
            {% endfor %}
        {% endif %}
    </body>
</html>