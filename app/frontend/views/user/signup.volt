<h1>{{ __('Sign up') }}</h1><hr />
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
{% set field = 'repeatPassword' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field]|isset ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|label) }}:</label>
    <div class="col-lg-10">
    {{ passwordField([ field, 'class' : 'form-control', 'placeholder' : __(field|label) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'email' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field]|isset ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'repeatEmail' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field]|isset ? ' has-success' : '') }}">
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
        {{ submitButton([ 'name' : 'submit_signin', 'class' : 'btn btn-default', __('Sign up') ]) }}
    </div>
</div>
{{ endForm() }}