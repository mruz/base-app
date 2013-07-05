{# Home View | base-app | 1.2 #}
<h1>{{ __('The base application in PhalconPHP') }}</h1>
<p>{{ __('Use this application as a way to quick start any new project.') }}</p>
<hr />
<h3>{{ __('Components') }}:</h3>
<ul>
    <li>Bootstrap file</li>
    <li>Config file</li>
    <li>Console file</li>
    <li>Volt template</li>
    <li>Frontend/Backend modules</li>
    <li>Library
        <ul>
            <li>Auth</li>
            <li>Arr</li>
            <li>Debug</li>
            <li>Email</li>
            <li>I18n</li>
            <li>Image</li>
            <li>Tool</li>
        </ul>
    </li>
    <li>User
        <ul>
            <li>Models</li>
            <li>Auth schema mysql</li>
        </ul>
    </li>
    <li>Twitter Bootstrap</li>
</ul>
<br />
<h3>{{ __('Configuration') }}:</h3>
<ol>
    <li>Set base_uri and other settings in /app/common/config/config.ini</li>
    <li>Use /auth-schema-mysql.sql to create required tables</li>
    <li>Make sure that these directories are writable by the web server:
        <ul>
            <li>/app/common/logs</li>
            <li>/app/common/cache</li>
            <li>/app/common/cache/volt</li>
            <li>/public/min/css</li>
            <li>/public/min/js</li>
            <li>/public/min/js/plugins</li>
        </ul>
    </li>
</ol>
<hr />
