#!/bin/bash
#===============================================================================
#
#          FILE:  export.sh
#
#         USAGE:  ./export.sh <db-user> <db-name>
#
#   DESCRIPTION:  Export aktuální struktury databáze.
#===============================================================================

if [ -z "$1" ] || [ -z "$2" ]; then
  echo 'Usage: ./export.sh <db-user> <db-name>'
else
 mysqldump -u$1 -p --add-drop-database --verbose $2 | gzip -9 > db.sql.gz
fi
