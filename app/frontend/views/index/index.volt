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
    <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/CLI', 'CLI', 'local': false, 'target': '_blank'])}} and Console file</li>
    <li>HMVC support</li>
    <li>{{ linkTo(['http://docs.phalconphp.com/en/latest/reference/volt.html', 'Volt', 'local': false, 'target': '_blank']) }} template</li>
    <li>Frontend/Backend/Cli modules</li>
    <li>Environment
        <ul>
            <li><em>development</em> - display debug, always compile template files, always minify assets</li>
            <li><em>staging</em> - log debug, notify admin, only checks for changes in the children templates, checks for changes and minify assets</li>
            <li><em>production</em> - log debug, notify admin, don't check for differences, don't create missing files, compiled and minified files must exist before!</li>
        </ul>
    </li>
    <li>Library
        <ul>
            <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/Arr', 'Arr', 'local': false, 'target': '_blank']) }}</li>
            <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/Auth', 'Auth', 'local': false, 'target': '_blank']) }}</li>
            <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/Debug', 'Debug', 'local': false, 'target': '_blank']) }}</li>
            <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/Email', 'Email', 'local': false, 'target': '_blank']) }}</li>
            <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/I18n', 'I18n', 'local': false, 'target': '_blank']) }}</li>
            <li>{{ linkTo(['https://github.com/mruz/base-app/wiki/Tool', 'Tool', 'local': false, 'target': '_blank']) }}</li>
        </ul>
    </li>
    <li>User
        <ul>
            <li>Models</li>
            <li>Auth schema mysql</li>
        </ul>
    </li>
    <li>Twitter Bootstrap 3.1.1</li>
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
    <li>{{ linkTo(['http://phalconphp.com', 'Phalcon PHP', 'local': false, 'target': '_blank']) }}</li>
    <li>{{ linkTo(['https://github.com/mruz/base-app', 'Base-app', 'local': false, 'target': '_blank']) }}</li>
    <li>{{ linkTo(['http://mruz.pl/base-app', 'Demo', 'local': false, 'target': '_blank']) }}</li>
    <li>{{ linkTo(['http://getbootstrap.com', 'Twitter Bootstrap', 'local': false, 'target': '_blank']) }}</li>
</ul>
<hr />