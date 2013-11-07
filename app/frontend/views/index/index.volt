{# Home View | base-app | 1.2 #}
<h1>{{ __('The base application in PhalconPHP') }}</h1>
<p>{{ __('Use this application as a way to quick start any new project.') }}</p>
<p>{{ __('See working :link, user/pass: :users.', [':link' : '<a href="http://mruz.pl/base-app">base-app</a>', ':users' : 'user user, admin admin']) }}
</p>
<hr />
<h3>{{ __('Features') }}:</h3>
<ul>
    <li>Bootstrap file</li>
    <li>Config file</li>
    <li>Console file</li>
    <li>HMVC support</li>
    <li>Volt template</li>
    <li>Frontend/Backend modules</li>
    <li>Library
        <ul>
            <li><a href="https://github.com/mruz/base-app/wiki/Arr" target="_blank">Arr</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/Auth" target="_blank">Auth</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/Debug" target="_blank">Debug</a></li>
            <li>Email</li>
            <li>I18n</li>
            <li>Tool</li>
        </ul>
    </li>
    <li>User
        <ul>
            <li>Models</li>
            <li>Auth schema mysql</li>
        </ul>
    </li>
    <li>Twitter Bootstrap 3.0.2</li>
</ul>
<br />
<h3>{{ __('Configuration') }}:</h3>
<ol>
    <li>Set <em class="text-info">base_uri</em> and other settings in <em class="text-info">/app/common/config/config.ini</em></li>
    <li>Use <em class="text-info">/auth-schema-mysql.sql</em> to create required tables</li>
    <li>Make sure that these directories are writable by the web server:
        <ul>
            <li><code>/app/common/logs</code></li>
            <li><code>/app/common/cache</code></li>
            <li><code>/public/min</code></li>
        </ul>
    </li>
</ol>
<h3>{{ __('Links') }}:</h3>
<ul>
    <li><a href="https://github.com/mruz/base-app" target="_blank">Github repo</a></li>
    <li><a href="https://github.com/mruz/base-app/issues" target="_blank">Issues</a></li>
    <li><a href="http://mruz.pl/base-app" target="_blank">Example</a></li>
    <li><a href="http://getbootstrap.com" target="_blank">Twitter Bootstrap</a></li>
</ul>
<hr />