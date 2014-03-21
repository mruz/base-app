{# User contact | base-app | 2.0 #}
<h1>{{ __('Contact') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
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
{% set field = 'fullName' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|label) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|label) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'content' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field]|isset ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
        {{ textarea([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize), 'rows': '5', 'onclick': "this.rows='10'" ]) }}
        {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
        {% endif %}
    </div>
</div>
<hr />
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button type="submit" name="submit" class="btn btn-default"><span class="glyphicon glyphicon-envelope"></span> {{ __('Send') }}</button>
    </div>
</div>
{{ endForm() }}