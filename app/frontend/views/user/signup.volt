{# User sign up | base-app | 2.0 #}
<h1>{{ __('Sign up') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
{% set field = 'username' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'password' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ passwordField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'repeatPassword' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|label) }}:</label>
    <div class="col-lg-10">
    {{ passwordField([ field, 'class' : 'form-control', 'placeholder' : __(field|label) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'email' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'repeatEmail' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|label) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|label) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
<hr />
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button type="submit" name="submit_signup" class="btn btn-default"><span class="glyphicon glyphicon-lock"></span> {{ __('Sign up') }}</button>
    </div>
</div>
{{ endForm() }}