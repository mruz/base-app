{# Email Template View | base-app | 1.3 #}
<div style="font: 11px/1.3 Helvetica; color: #333">
    <h1 style="font-size: 15px; line-height: 20px"><a href="{{ url.getStatic() }}">{{ config.app.name }}</a></h1>
    <hr style="border: 0; margin: 0; border-bottom: 1px solid #ddd" />
    {{ content() }}
    <hr style="border: 0; margin: 0; border-bottom: 1px solid #ccc" />
    <p style="color: #999">&copy; {{ config.app.name }} {{ date('Y') }}</p>
</div>