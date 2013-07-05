<h1>{{ __('Sign in') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
{% set field = 'username' %}
<div class="control-group{{ errors is defined and errors.filter(field) ? ' error' : (_POST[field]|isset ? ' success' : '') }}">
    <label class="control-label" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="controls">
    {{ textField([ field, 'class' : 'span2', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-inline">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'password' %}
<div class="control-group{{ errors is defined and errors.filter(field) ? ' error' : (_POST[field]|isset ? ' success' : '') }}">
    <label class="control-label" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="controls">
    {{ passwordField([ field, 'class' : 'span2', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-inline">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
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
    <p>{{ submitButton([ 'name' : 'submit_signin', 'class' : 'btn', __('Sign in') ]) }}</p>
    <p class="muted">
        {{  __("Don't have an account?") }} {{ linkTo([ 'user/signup', __('Sign up') ~ ' »' ]) }}
    </p>
</div>
{{ endForm() }}