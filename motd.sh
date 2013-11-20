#!/bin/sh

# Written for Debian/Ubuntu
# dushankw - http://dushan.it

echo "\nWelcome to `lsb_release -sd` (`uname -rm`)"
echo "`hostname` up for `uptime | awk '{print $3 " " $4 }'` load average `cat /proc/loadavg`\n"
