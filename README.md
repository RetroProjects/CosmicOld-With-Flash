# <img src="https://habborator.org/archive/icons/medium/go_arrow.gif"> About:

> ![version](https://img.shields.io/badge/production-2.5-green?logo=appveyor&style=flat-square) ![unstable](https://img.shields.io/badge/stability-stable-green?logo=appveyor&style=flat-square)


# <img src="https://raw.githubusercontent.com/Wulles/eyethatseeseverything/master/pwrup_pins.gif"> Installation

<a href="https://git.krews.org/Raizer/cosmic-assets">Get Cosmic Assets (Database & Webkit Plugin)</a>

<a href="https://vimeo.com/396311594">IIS Video Tutorial - Cosmic Installation, Morningstar Arcturus & Catalogue Cosmic</a>

# Cosmic

Cosmic is builded with Simple Router, fast and yet powerful PHP router that is easy to get integrated and in any project. Heavily inspired by the way Laravel handles routing, with both simplicity and expand-ability in mind.

### Support the project

If you like Cosmic and wish to see the continued development and maintenance of the project, please consider showing your support by buying me a coffee. Supporters will be listed under the credits section of this documentation.

You can donate any amount of your choice by [clicking here](https://www.paypal.com/donate?hosted_button_id=VQBLKBH8FNKDW).

# Navigation


- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Features](#features)
	- [Installation](#installation)
        - [Setting up Nginx](#setting-up-nginx)
		- [Setting up Apache](#setting-up-apache)
		- [Setting up IIS](#setting-up-iis)
		- [Configuration](#configuration)
		- [Helper functions](#helper-functions)

___

# Getting started

Get [git bash](https://git-scm.com/downloads) and get the latest version of Cosmic project running this command.

```
git clone https://git.krews.org/Raizer/Cosmic.git ./
```

## Requirements

- PHP 8.0.1 or lower >= 7.1 
- PHP JSON extension enabled.
- PHP CURL extension enabled.
- PHP MBSTRING extension enabled.

[How to Enable PHP extensions](https://www.php.net/manual/en/install.pecl.windows.php)

### Setting up Nginx

If you are using Nginx please make sure that url-rewriting is enabled.

You can easily enable url-rewriting by adding the following configuration for the Nginx configuration-file.

```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

If you using cloudflare make sure you whitelist cloudflare ipadresses and set the correct header as response to Cosmic.

Create file inside `/opt/scripts/` called `cloudflare-ip-whitelist-sync.sh`

paste the following code inside of it
```
#!/bin/bash

CLOUDFLARE_FILE_PATH=/etc/nginx/cloudflare

echo "#Cloudflare" > $CLOUDFLARE_FILE_PATH;
echo "" >> $CLOUDFLARE_FILE_PATH;

echo "# - IPv4" >> $CLOUDFLARE_FILE_PATH;
for i in `curl https://www.cloudflare.com/ips-v4`; do
        echo "set_real_ip_from $i;" >> $CLOUDFLARE_FILE_PATH;
done

echo "" >> $CLOUDFLARE_FILE_PATH;
echo "# - IPv6" >> $CLOUDFLARE_FILE_PATH;
for i in `curl https://www.cloudflare.com/ips-v6`; do
        echo "set_real_ip_from $i;" >> $CLOUDFLARE_FILE_PATH;
done

echo "" >> $CLOUDFLARE_FILE_PATH;
echo "real_ip_header CF-Connecting-IP;" >> $CLOUDFLARE_FILE_PATH;

#test configuration and reload nginx
nginx -t && systemctl reload nginx
```

give it execute permissions `sudo chmod +x /opt/scripts/cloudflare-ip-whitelist-sync.sh`

run it by using this command `sudo sh /opt/scripts/cloudflare-ip-whitelist-sync.sh`

Add the line below inside the tags `/etc/nginx/nginx.conf`
```
# inside the http block
http { 
   include /etc/nginx/cloudflare 
   }
```

create a cron job with the following details
```
# Auto sync ip addresses of Cloudflare and reload nginx
30 2 * * * /opt/scripts/cloudflare-ip-whitelist-sync.sh >/dev/null 2>&1
```

restart nginx using `sudo systemctl restart nginx`

### Setting up Apache

Nothing special is required for Apache to work. We've include the `.htaccess` file in the `public` folder. If rewriting is not working for you, please check that the `mod_rewrite` module (htaccess support) is enabled in the Apache configuration.

#### .htaccess example

Below is an example of an working `.htaccess` file used by simple-php-router.

Simply create a new `.htaccess` file in your projects `public` directory and paste the contents below in your newly created file. This will redirect all requests to your `index.php` file (see Configuration section below).

```
RewriteEngine On
SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1

Header add Access-Control-Allow-Origin "*"

RewriteRule ^habbo-imaging/avatarimage$ habbo-imaging/avatarimage.php [QSA,L]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l

RewriteRule ^(.*)$ index.php?$1 [L,QSA]

```

### Setting up IIS

On IIS you have to add some lines your `web.config` file in the `public` folder or create a new one. If rewriting is not working for you, please check that your IIS version have included the `url rewrite` module or download and install them from Microsoft web site.

#### web.config example

Below is an example of an working `web.config` 

Simply create a new `web.config` file in your projects `public` directory and paste the contents below in your newly created file. This will redirect all requests to your `index.php` file (see Configuration section below). If the `web.config` file already exists, add the `<rewrite>` section inside the `<system.webServer>` branch.

```
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
   <system.webServer>
      <rewrite>
         <rules>
            <rule name="habbo-imaging" stopProcessing="true">
               <match url="^habbo-imaging/avatarimage$" ignoreCase="false" />
               <action type="Rewrite" url="habbo-imaging/avatarimage.php" appendQueryString="true" />
            </rule>
            <rule name="Imported Rule 1" stopProcessing="true">
               <match url="^(.*)$" ignoreCase="false" />
               <conditions logicalGrouping="MatchAll">
                  <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                  <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
               </conditions>
               <action type="Rewrite" url="index.php?{R:1}" appendQueryString="true" />
            </rule>
            <rule name="Rule1">
               <match url=".*" />
               <serverVariables>
                  <set name="REMOTE_ADDR" value="{HTTP_CF_Connecting_IP}" />
               </serverVariables>
               <action type="None" />
            </rule>
         </rules>
      </rewrite>
      <staticContent>
         <mimeMap fileExtension="." mimeType="text/xml" />
      </staticContent>
   </system.webServer>
</configuration>
```

Once you added the web.config open IIS manager, navigate to `sites` -> `default(or project)` -> `url rewrite`. Click under action on `View Server Variables`. Add name `REMOTE_ADDR` with entity type `Local`. 
