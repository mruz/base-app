{# Activation View | base-app | 2.0 #}
<p>{{ __('Hello :user', [':user' : username]) }},</p>
<p>{{ __('Before you can log in, you first need to activate your account.') }}</p>
<p>{{ __('To activate your account, click on this link:') }} <a href="{{ url.getStatic('user/activation/' ~ username ~ '/' ~ hash) }}">{{ __('Activation') }}</a>.</p>