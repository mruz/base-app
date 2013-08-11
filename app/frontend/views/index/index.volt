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
    <li>Twitter Bootstrap v3</li>
</ul>
<br />
<h3>{{ __('Configuration') }}:</h3>
<ol>
    <li>Set <em class="text-info">base_uri</em> and other settings in <em class="text-info">/app/common/config/config.ini</em></li>
    <li>Use <em class="text-info">/auth-schema-mysql.sql</em> to create required tables</li>
    <li>Make sure that these directories are writable by the web server:
        <ul>
            <li><em class="text-info">/app/common/logs</em></li>
            <li><em class="text-info">/app/common/cache</em></li>
            <li><em class="text-info">/app/common/cache/volt</em></li>
            <li><em class="text-info">/public/min/css</em></li>
            <li><em class="text-info">/public/min/js</em></li>
            <li><em class="text-info">/public/min/js/plugins</em></li>
        </ul>
    </li>
</ol>
<hr />
