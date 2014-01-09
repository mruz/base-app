{# Home View | base-app | 2.0 #}
<h1>base-app 2.0</h1>
<h5>{{ __('The base application in PhalconPHP') }}</h5>
<p>{{ __('Use this application as a way to quick start any new project.') }} {{ __('See working :link, user/pass: :users.', [':link' : '<a href="http://mruz.pl/base-app">base-app</a>', ':users' : 'user user, admin admin']) }}
</p>
<hr />
<h3>{{ __('Features') }}:</h3>
<ul>
    <li>Bootstrap file</li>
    <li>Config file</li>
    <li>Console file</li>
    <li>HMVC support</li>
    <li>Volt template</li>
    <li>Frontend/Backend/Cli modules</li>
    <li>Environment
        <ul>
            <li><em>development</em> - display debug, always compile template files, checks for changes and minify assets</li>
            <li><em>staging</em> - log debug, notify admin, only checks for changes in the children templates, checks for changes and minify assets</li>
            <li><em>production</em> - log debug, notify admin, don't check for differences, don't create missing files, compiled and minified files must exist before!</li>
        </ul>
    </li>
    <li>Library
        <ul>
            <li><a href="https://github.com/mruz/base-app/wiki/Arr" target="_blank">Arr</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/Auth" target="_blank">Auth</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/Debug" target="_blank">Debug</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/Email" target="_blank">Email</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/I18n" target="_blank">I18n</a></li>
            <li><a href="https://github.com/mruz/base-app/wiki/Tool" target="_blank">Tool</a></li>
        </ul>
    </li>
    <li>User
        <ul>
            <li>Models</li>
            <li>Auth schema mysql</li>
        </ul>
    </li>
    <li>Twitter Bootstrap 3.0.3</li>
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
<h3>{{ __('Requirements') }}:</h3>
<ul>
    <li>Phalcon <strong>2.0.0</strong></li>
</ul>
<h3>{{ __('Links') }}:</h3>
<ul>
    <li><a href="http://phalconphp.com" target="_blank">Phalcon PHP</a></li>
    <li><a href="https://github.com/mruz/base-app" target="_blank">Github repo</a></li>
    <li><a href="https://github.com/mruz/base-app/issues" target="_blank">Issues</a></li>
    <li><a href="http://mruz.pl/base-app" target="_blank">Example</a></li>
    <li><a href="http://getbootstrap.com" target="_blank">Twitter Bootstrap</a></li>
</ul>
<hr />