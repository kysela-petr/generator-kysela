#<%= appName %>

## Základní informace ##
eSports Nette based web project <%= appName %>

Zodpovědné osoby
================

manager: <%= manager %>
designer: <%= designer %>
backend developer: <%= backend %>
frontend developer: <%= frontend %>


Mazání cache na serveru i lokálně
=================================

Spuštění skriptu host/cleanTemp.php


StepByStep pro nastavení projektu, který poběží lokálně na <%= virtualHostUrl %>
================================================================================
1. Naklonovat projekt do lokální složky
2. Nastavit soubor aliasy do /etc/hosts

```
	127.0.1.1	<%= virtualHostUrl %>
```

3. Na lokálním serveru vytvořit virtualhost :

```
	<VirtualHost *:80>
		ServerName <%= virtualHostUrl %>
		ServerAdmin webmaster@<%= virtualHostUrl %>
	
		DocumentRoot <složka projektu>/www
		<Directory />
			Options FollowSymLinks
			AllowOverride All
		</Directory>
		<Directory <složka projektu>/www>
			Options Indexes FollowSymLinks MultiViews
			AllowOverride All
			Require all granted	
		</Directory>
		ErrorLog <složka projektu>/error.log
		LogLevel warn
		CustomLog <složka projektu>/log.log combined
	</VirtualHost>
```

4. Restart apache

```#!bash sudo service apache2 restart```

5. Instalace závislostí PHP


```#!bash cd "složka projektu" composer update```

6. Databáze

Založit si databázi, naimportovat do databáze *db/dummy/structures.sql*, potom základní data *db/dummy/data.sql*

```
mysql -u <user>  -p<heslo> <jmenodatabase> < structures.sql
```

```
mysql -u <user> -p<heslo> <jmenodatabase> < data.sql
```


7. Zkopírovat a app/config/config.local.neon.template na config.local.neon nastavit přihlášení do DB

```
cp  config.local.neon.template config.local.neon
```


8.Spustit v prohlížeči url **<%= virtualHostUrl %>**, **<%= virtualHostUrl %>/admin**, přihlašovací údaje *:-)*