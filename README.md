# WordPress
Bastille template to install and configure WordPress with some sane defaults.

* You only need to add a Site Title, Username, Password and Email address in the Welcome Wizard.
* Some sane defaults:
  * Installs the latest most up-to-date version of WordPress.
  * WordPress, plugins and themes can be installed/updated via the `wp-admin` backend.
  * Fancy custom URL structures (Permalinks) work out of the box.
  * WordPress files are owned by user/group `wordpress` and have restrictive permissions.
  * `wp-config.php` is installed outside of the WordPress webroot directory.
  * Access to `wp-config.php`, scripts in `wp-includes` and XML-RPC are disabled via `.htaccess`.
  * (Automated) author scans to scrape valid WordPress accounts are blocked.
  * The `wp-admin` backend editor isn't allowed to edit (PHP) files.
  * The `wp-admin` backend has server-side password protection (`BasicAuth`).
  * All passwords are randomly generated and strong.
* Defaults to a stand-alone WordPress jail. Reverse proxies are supported as well (see WordPress behind proxy).
* Securing/Hardening WordPress further is possible (see Harden Wordpress).
* Converting to WordPress Multisite is supported (see WordPress Multisite).

After running the template, the configuration details can be found in `/root/.wordpress`.

## Requirements
* Requires a webserver with PHP support (for which templates are available as well).
* A working webserver configuration with **`/usr/local/www/wordpress` as the webroot**.
* `RewriteRules` and `+FollowSymLinks` or `+SymLinksIfOwnerMatch` must be enabled.
* A working internet connection that can reach https://wordpress.org and FreeBSD's package repository.
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

## WordPress Multisite
After the WordPress instance has been installed and initialized, the WordPress instance can be converted to a [WordPress Multisite](https://wordpress.org/documentation/article/wordpress-glossary/#network). But you will have to follow a few steps for this.

1. Remove `RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]` from `.htaccess`.
2. Deactivate any active plugin (Administration -> Plugins).
3. Set the [Custom URL Structure](https://wordpress.org/documentation/article/customize-permalinks/) (Administration -> Settings -> Permalinks) to something prettier than "Plain" (e.g. "Post name"/`https://domain.tld/sample-post/`) and verify whether the new Custom URL Structure is applied succesfully.
4. Add `define( 'WP_ALLOW_MULTISITE', true );` to `wp-config.php`. Preferably between `/* Add any custom values ... */` and `/* That's all, stop editing! ... */`.
5. Refresh `wp-admin` and open Network Setup (Administration -> Tools -> Network Setup). Note that it can take a few minutes before Network Setup is visible because of caching.
6. Configure the Multisite to taste press "Install". Add the listed changes to `wp-config.php` (Preferably between `/* Add any custom values ... */` and `/* That's all, stop editing! ... */`.) and `.htaccess`.
7. Refresh WordPress and login again. Your WordPress instance is now converted to a WordPress Multisite \o/.

## Harden Wordpress
This template strikes a balance between security and being accessible/useful for users. If you feel these settings are not paranoid enough, WordPress can be hardened further as follows:

### Restrict ownership further
Change ownership of `wp-content` and all subdirectories (including `wp-content`) to `wordpress`:`wordpress` (i.e. `chown -R wordpress:wordpress /usr/local/www/wordpress`). This makes sure Wordpress (or an adversary) can't manipulate plugins, themes and other content. But as a consequence you as well won't be able to administer plugins, themes and other content via the `wp-admin` backend. One possible strategy is to ease these restrictions when you're administering the website but keep them tight when you're not. Another strategy is to use `WP-CLI`, a command line interface tool for WordPress that effectively lets you bypass the `wp-admin` backend for the most part.

### Restrict database privileges
By default this template grants all database privileges to the MySQL user `wordpress`. For normal WordPress operations the MySQL user only needs data read and data write privileges (i.e. `SELECT`, `INSERT`, `UPDATE` and `DELETE`). Limiting these privileges improves the security of WordPress. Do note that some plugins, themes and major WordPress updates might require more privileges. Your mileage may vary.

### Restrict access to `wp-admin` further
By default this template enables Basic Authentication (username/password authentication) on the `wp-admin` backend. Although potential adversaries can't access `wp-admin` this way, they can still try to brute-force the BasicAuth credentials. The easiest way to mitigate this risk is by allowing only certain IP addresses to access `wp-admin`. This can be one (e.g. home) or multiple (e.g. for home, work and VPN) IP addresses. To do this, add the following to `/usr/local/www/wordpress/wp-admin/.htaccess`: 
```
# require IP address
require ip x.x.x.x # home IP
require ip x.x.x.x # work IP
require ip x.x.x.x # VPN IP
require ip x.x.x.x/28 # subnet
satisfy any
```
If you want to circumvent the Basic Authentication (that is setup by default) when you connect from one of these IP addresses, use `satisfy any`. Note that increasing the amount of rules significantly can impact performance negatively.

## Support
Templates will be maintained until their respective software version is end-of-life. Repositories will then be archived and removed from any meta-templates.

If you have a question, suggestion or find a bug, please let us know via an Issues in the relevant repository or send us an email.

## License
All templates are distributed under the 3-Clause BSD License. See `LICENSE` in every template repository for more information.
