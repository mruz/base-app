{# User View | base-app | 2.0 #}
<h2>{{ __('Hello :user', [':user' : auth.get_user().username]) }}</h2><hr />
<p class="muted">{{ __('Have a nice day!') }}</p>
<p><strong>{{ __('Logins') }}:</strong> {{ auth.get_user().logins }}</p>
<p><strong>{{ __('Last login') }}:</strong> {{ date('Y-m-d H:i:s', auth.get_user().last_login) }}</p>
<p>{{ linkTo('user/signout', __('Sign out') ) }}</p>