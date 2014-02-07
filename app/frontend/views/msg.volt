{# Messages View | base-app | 2.0 #}
<div>
    <h2>{{ title }}</h2><hr />
    <meta http-equiv="Refresh" content="5; url={{ config.app.base_uri ~ redirect|default('') }}" />
    {{ flashSession.output() }}
    {{ content|default('') }}
</div>