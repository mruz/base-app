{# Messages View | base-app | 1.3 #}
<div>
    <h2>{{ title }}</h2><hr />
    <meta http-equiv="Refresh" content="5; url={{ config.app.base_uri ~ redirect|isset }}" />
    {{ flashSession.output() }}
    {{ content|isset }}
</div>