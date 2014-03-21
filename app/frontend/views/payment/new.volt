<h2>{{ __('Payment') ~ ' ' ~ __('New') }}</h2><hr />
<p>{{ __('Payments are supported by') ~ ': ' ~ adapter }}</p><br />
{{ flashSession.output() }}
{{ form(NULL, 'class' : 'form-horizontal') }}
{% set field = 'firstname' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
{% set field = 'lastname' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textField([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize) ]) }}
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
{% set field = 'note' %}
<div class="form-group{{ errors is defined and errors.filter(field) ? ' has-error' : (_POST[field] is defined ? ' has-success' : '') }}">
    <label class="control-label col-lg-2" for={{ field }}>{{ __(field|capitalize) }}:</label>
    <div class="col-lg-10">
    {{ textarea([ field, 'class' : 'form-control', 'placeholder' : __(field|capitalize), 'rows': '1', 'onclick': "this.rows='3'" ]) }}
    {% if errors is defined and errors.filter(field) %}
        <span class="help-block">{{ current(errors.filter(field)).getMessage() }}</span>
    {% endif %}
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button type="submit" name="submit" class="btn btn-default"> {{ __('Go to payment') }}</button>
    </div>
</div>
{{ endForm() }}