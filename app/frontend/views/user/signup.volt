<h1>{{ __('Sign up') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
<div class="control-group{#{ errors is defined and errors.filter('username') ? ' error' : _POST['username']|isset ? ' success' : '' }#}">
    <label class="control-label" for="username">{{ __('Username') }}:</label>
    <div class="controls">
    {{ textField([ 'username', 'class' : 'span2', 'placeholder' : __('Username') ]) }}
    <span class="help-inline">{#{ errors is defined and errors.filter('username') }#}</span>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="password">{{ __('Password') }}:</label>
    <div class="controls">
    {{ passwordField([ 'password', 'class' : 'span2', 'placeholder' : __('Password') ]) }}
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="username">{{ __('Email') }}:</label>
    <div class="controls">
    {{ textField([ 'email', 'class' : 'span2', 'placeholder' : __('Email') ]) }}
    <span class="help-inline"></span>
    </div>
</div>
<div class="form-actions">
    {{ submitButton([ 'name' : 'submit_signin', 'class' : 'btn', __('Sign up') ]) }}
</div>
{{ endForm() }}

{#{ debug(_POST) }}
{{ debug(json_encode(errors.filter('email')) ) }}}
{{ debug(errors) }#}