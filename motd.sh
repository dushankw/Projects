#!/bin/sh
echo "\nWelcome to `lsb_release -sd` (`uname -rm`)"
echo "`hostname` up for `uptime | awk '{print $3 " " $4 }'` load average `cat /proc/loadavg`\n"
