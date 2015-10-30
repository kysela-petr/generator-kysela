#!/bin/bash
#===============================================================================
#
#          FILE:  import.sh
#
#         USAGE:  ./import.sh <db-user> <db-name>
#
#   DESCRIPTION:  Import aktuální struktury databáze.
#===============================================================================
if [ -z "$1" ] || [ -z "$2" ]; then
  echo 'Usage: ./import.sh <db-user> <db-name>'
else
 gunzip < db.sql.gz | mysql -u$1 -p $2
fi
