#!/bin/bash
MY_PLACES_DIR=/Users/markushi/vhosts/myplaces_com/webdocs
WEB_DIR=/Users/markushi/Git/00webhost/htdocs
EXCLUDE_LIST=/Users/markushi/Git/00webhost/script/exclude_myplaces.lst
rsync -vr --exclude-from=$EXCLUDE_LIST ${MY_PLACES_DIR}/ ${WEB_DIR}/
