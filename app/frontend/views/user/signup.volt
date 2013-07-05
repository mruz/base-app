<h1>{{ __('Sign up') }}</h1><hr />
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
{% set field = 'repeatPassword' %}
<div class="control-group{{ errors is defined and errors.filter(field) ? ' error' : (_POST[field]|isset ? ' success' : '') }}">
    <label class="control-label" for={{ field }}>{{ __(field|label) }}:</label>
    <div class="controls">
    {{ passwordField([ field, 'class' : 'span2', 'placeholder' : __(field|label) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-inline">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'email' %}
<div class="control-group{{ errors is defined and errors.filter(field) ? ' error' : (_POST[field]|isset ? ' success' : '') }}">
    <label class="control-label" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="controls">
    {{ textField([ field, 'class' : 'span2', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-inline">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'repeatEmail' %}
<div class="control-group{{ errors is defined and errors.filter(field) ? ' error' : (_POST[field]|isset ? ' success' : '') }}">
    <label class="control-label" for={{ field }}>{{ __(field|label) }}:</label>
    <div class="controls">
    {{ textField([ field, 'class' : 'span2', 'placeholder' : __(field|label) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-inline">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
<div class="form-actions">
    {{ submitButton([ 'name' : 'submit_signin', 'class' : 'btn', __('Sign up') ]) }}
</div>
{{ endForm() }}