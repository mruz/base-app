{# Error View | base-app | 1.3 #}
<p>{{ __('Hello :user', [':user' : 'admin']) }}</p>
<p><stron>{{ __('Something is wrong!') }}</strong></p>
<p>{{ __('Look at the log:') }}</p>
{{ log }}