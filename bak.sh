#!/bin/bash

# Simple Backup Sript
# Written by Dushan (http://dushan.it)

# Variables used in script
OWNER=dushan
BAKPATH="/backup/"
TODAY=`date +%d%m%y`

# Make the tar.gz archives
tar -zcvf $BAKPATH/home.$TODAY.tar.gz /home/
tar -zcvf $BAKPATH/etc.$TODAY.tar.gz /etc/

# Set the owner of the files
chown $OWNER:$OWNER $BAKPATH/*.tar.gz
