#!/bin/bash

# Written for Debian/Ubuntu
# dushankw - http://dushan.it

# This script allows you to customise the SSH MOTD
# Install "update-motd" with "sudo apt-get install update-motd"
# Delete the contents of /etc/update-motd.d/ then copy this file there
# Set permissions "chmod 700 <filename>"
# Wait a few mins for the update-motd cronjob to pick it up
# Log out and then SSH in again, you will see the new MOTD

echo ""
echo "Welcome to `lsb_release -sd` (`uname -rm`)"
echo "`hostname` up for `uptime | awk '{print $3 " " $4 " load average " $9 " " $10 " " $11}'`"
echo ""
