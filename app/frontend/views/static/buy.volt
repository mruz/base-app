{# Example buy view | base-app | 2.0 #}
<h1>{{ __('Buy me some chocolate') }}</h1><hr />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
<div class="form-group">
    <label class="control-label col-lg-2">{{ __('Price') }}:</label>
    <div class="col-lg-10">
        <p class="form-control-static">1USD</p>
    </div>
</div>
{% set field = 'quantity' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'value': 1, 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <p><button type="submit" name="submit" class="btn btn-default"><span class="glyphicon glyphicon-gift"></span> {{ __('Buy now') }}</button></p>
    </div>
</div>
{{ endForm() }}