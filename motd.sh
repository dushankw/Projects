#!/bin/sh

# Written for Debian/Ubuntu
# dushankw - http://dushan.it

# This script changes the SSH MOTD to my liking
# Install "update-motd" with "sudo apt-get install update-motd"
# Delete the contents of /etc/update-motd.d/ then copy this file there
# Set permissions "chmod 700 <filename>"
# Wait a few mins for the update-motd cronjob to pick it up
# Log out and then SSH in again, you will see the new MOTD

echo "\nWelcome to `lsb_release -sd` (`uname -rm`)"
echo "`hostname` up for `uptime | awk '{print $3 " " $4 }'` load average `cat /proc/loadavg`\n"
