#!/bin/bash
path=`echo "$0" | sed -e "s/[^\/]*$//"`
INI_FILE=$path/../../../../../app/config/parameters.ini
INI_SECTION=parameters
MYSQLDUMP="/usr/bin/mysqldump"

DUMP_FILE=$path"/../Resources/dump/dump_`date +%Y%m%d_%H%M`.sql"

# Load data from ini file
eval `sed -e 's/[[:space:]]*\=[[:space:]]*/=/g' \
    -e 's/;.*$//' \
    -e 's/[[:space:]]*$//' \
    -e 's/^[[:space:]]*//' \
    -e "s/^\(.*\)=\([^\"']*\)$/\1=\"\2\"/" \
   < $INI_FILE \
    | sed -n -e "/^\[$INI_SECTION\]/,/^\s*\[/{/^[^;].*\=.*/p;}"`

$MYSQLDUMP -u$database_user -p$database_password -h$database_host $database_name > $DUMP_FILE
