<h1>{{ __('Sign in') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
<div class="control-group">
    <label class="control-label" for="username">{{ __('Username') }}:</label>
    <div class="controls">
    {{ textField([ 'username', 'class' : 'span2', 'placeholder' : __('Username') ]) }}
    <span class="help-inline"></span>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="password">{{ __('Password') }}:</label>
    <div class="controls">
    {{ passwordField([ 'password', 'class' : 'span2', 'placeholder' : __('Password') ]) }}
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <label class="checkbox">
            {{ checkField([ 'remember' ]) }} {{ __('Remember me') }}
        </label>
    </div>
</div>
<div class="form-actions">
    {{ submitButton([ 'name' : 'submit_signin', 'class' : 'btn', __('Sign in') ]) }}
</div>
{{ endForm() }}
{{ linkTo([ 'user/signup', __('Sign up') ]) }}