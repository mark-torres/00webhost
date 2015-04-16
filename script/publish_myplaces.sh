#!/bin/bash
CONFIG=/Users/markushi/Git/00webhost/script/00webhost.cfg
WEB_ROOT=/Users/markushi/Git/00webhost/htdocs
# COPY FOLDERS
ncftpput -f $CONFIG -Rm /public_html ${WEB_ROOT}/application
ncftpput -f $CONFIG -Rm /public_html ${WEB_ROOT}/system
ncftpput -f $CONFIG -Rm /public_html ${WEB_ROOT}/css
ncftpput -f $CONFIG -Rm /public_html ${WEB_ROOT}/img
ncftpput -f $CONFIG -Rm /public_html ${WEB_ROOT}/js
# COPY INDIVIDUAL FILES
ncftpput -f $CONFIG -m /public_html ${WEB_ROOT}/index.php
ncftpput -f $CONFIG -m /public_html ${WEB_ROOT}/.htaccess
