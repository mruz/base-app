{# Admin's Template View | base-app | 1.2 #}
<!DOCTYPE html>
<html lang="{{ substr(i18n.lang(), 0, 2) }}">
    <head>
        <meta charset="utf-8">
        {{ getTitle() }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {{ stylesheetLink('css/bootstrap.min.css') }}
        <style>
            body { padding-top: 45px; }
        </style>
        {{ stylesheetLink('css/bootstrap-responsive.min.css') }}
        {{ stylesheetLink('css/app.css') }}

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        {{ javascriptInclude('js/html5shiv.js') }}
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="{{ url.get('favicon.ico') }}">
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
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
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            {{ content() }}
        </div> <!-- /container -->

        {{ javascriptInclude('js/jquery.min.js') }}
        {{ javascriptInclude('js/bootstrap.min.js') }}
    </body>
</html>