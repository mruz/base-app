# base-app 1.2

##### The base application in PhalconPHP
Use this application as a way to quick start any new project.
***
### Components:
* Bootstrap file
* Config file
* Console file
* Volt template
* Frontend/Backend modules
* Library
 * [Arr](https://github.com/mruz/base-app/wiki/Arr)
 * [Auth](https://github.com/mruz/base-app/wiki/Auth)
 * [Debug](https://github.com/mruz/base-app/wiki/Debug)
 * [Email](https://github.com/mruz/base-app/wiki/Email)
 * [I18n](https://github.com/mruz/base-app/wiki/I18n)
 * [Image](http://kohanaframework.org/3.3/guide/image/using)
 * [Tool](https://github.com/mruz/base-app/wiki/Tool)
* User
 * Models
 * Auth schema mysql
* Twitter Bootstrap 3.0.0

### Configuration:
1. Set *base_uri* and other settings in */app/common/config/config.ini*
2. Use */auth-schema-mysql.sql* to create required tables
3. Make sure that these directories are writable by the web server:
 * `/app/common/logs`
 * `/app/common/cache`
 * `/app/common/cache/volt`
 * `/public/min/css`
 * `/public/min/js`
 * `/public/min/js/plugins`
