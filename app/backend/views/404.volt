{# Error 404 View | base-app | 1.3 #}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ __('Error :code', [':code' : 404]) }}</title>
    </head>
    <body>
        <h1>{{ __('Error :code', [':code' : 404]) }} - {{ __('Page not found.') }}</h1>
    </body>
</html>