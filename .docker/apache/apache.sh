#! /bin/bash

#/usr/sbin/apache2ctl -D FOREGROUND

# Apache gets grumpy about PID files pre-existing
rm -f /var/run/apache2/apache2*.pid

source /etc/apache2/envvars

a2ensite default
a2enmod authz_groupfile
# a2enmod authn_dbd
# a2enmod authn_socache
a2enmod proxy
a2enmod proxy_fcgi
a2enmod rewrite
a2enmod allowmethods

service apache2 reload

exec apache2 -DFOREGROUND
