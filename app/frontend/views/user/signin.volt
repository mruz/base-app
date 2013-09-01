<h1>{{ __('Sign in') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
{% set field = 'username' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field]|isset ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'password' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field]|isset ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ passwordField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <label class="checkbox">
            {{ checkField([ 'remember' ]) }} {{ __('Remember me') }}
        </label>
    </div>
</div>
<hr />
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <p><button type="submit" name="submit_signin" class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span> {{ __('Sign in') }}</button></p>
        <p class="text-muted">
            {{  __("Don't have an account?") }} {{ linkTo([ 'user/signup', __('Sign up') ~ ' »' ]) }}
        </p>
    </div>
</div>
{{ endForm() }}