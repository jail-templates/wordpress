# WordPress
Bastille template to install and configure WordPress with some sane defaults.

* You only need to add a Site Title, Username, Password and Email address in the Welcome Wizard.
* Some sane defaults:
  * Installs the latest most up-to-date version of WordPress.
  * WordPress, plugins and themes can be installed/updated via the `wp-admin` backend.
  * Most WordPress files are owned by user/group `wordpress` (not the webserver).
  * Restrictive permissions on all WordPress files, including `.htaccess`.
  * `wp-config.php` is not part of the WordPress webroot directory.
  * Access to `wp-config.php` is further restricted via `.htaccess`.
  * Access to scripts in `wp-includes` is further restructed via `.htaccess`.
  * The `wp-admin` backend editor isn't allowed to edit (PHP) files.
  * The `wp-admin` backend is protected with server-side password protection (`BasicAuth`).
  * The database and system account have strong randomly generated passwords.
* After running the template, the configuration details can be found in `/root/.wordpress`.
* Securing WordPress further is possible (see Hardening Wordpress).

## Requirements
* Requires a webserver with PHP support (for which templates are available as well).
* A working webserver configuration with `/usr/local/www/wordpress` as the webroot.
* `+FollowSymLinks` or `+SymLinksIfOwnerMatch` must be enabled.
* Ideally you have configured a firewall on the jail host, TLS and security headers.

## Bootstrap
```
bastille bootstrap https://github.com/jail-templates/wordpress
```

## Apply template
```
bastille template $JAIL jail-templates/wordpress
```

## WordPress behind proxy
Add the following to `wp-config.php` and replace `domain.tld` with your domain name to run WordPress behind a proxy:
```
// ** Proxy settings ** //
define('.COOKIE_DOMAIN.', 'domain.tld');
define('.SITECOOKIEPATH.', '.');

if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $list = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = $list[0];
  }
define( 'WP_HOME', 'https://domain.tld' );
define( 'WP_SITEURL', 'https://domain.tld' );
$_SERVER['HTTP_HOST'] = 'domain.tld';
$_SERVER['REMOTE_ADDR'] = 'domain.tld';
$_SERVER[ 'SERVER_ADDR' ] = 'domain.tld';

if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
       $_SERVER['HTTPS']='on';
```

## Hardening Wordpress
This template strikes a balance between security and being accessible/useful for users. If you feel these settings are not paranoid enough, WordPress can be hardened further as follows:

* Change ownership of `wp-content` and all subdirectories from `www`:`www` to `wordpress`:`wordpress` (i.e. `chown -R wordpress:wordpress /usr/local/www/wordpress/wp-content`). This makes sure Wordpress (or an adversary) can't manipulate plugins, themes and other content. But as a consequence you as well won't be able to administer plugins, themes and other content via the `wp-admin` backend. One possible strategy is to ease these restrictions when you're administering the website but keep them tight when you're not. Another strategy is to use `WP-CLI`, a command line interface tool for WordPress that effectively lets you bypass the `wp-admin` backend for the most part.
* By default this template grants all database privileges to the MySQL user `wordpress`. For normal WordPress operations the MySQL user only needs data read and data write privileges (i.e. `SELECT`, `INSERT`, `UPDATE` and `DELETE`). Limiting these privileges improves the security of WordPress. Do note that some plugins, themes and major WordPress updates might require more privileges. Your mileage may vary.

## Support
Templates will be maintained until their respective software version is end-of-life. Repositories will then be archived and removed from any meta-templates.

If you have a question, suggestion or find a bug, please let us know via an Issues in the relevant repository or send us an email.

## License
All templates are distributed under the 3-Clause BSD License. See `LICENSE` in every template repository for more information.
