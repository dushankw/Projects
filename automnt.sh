#!/bin/bash

# Written by Dushan
# http://dushan.it

# This script will mount a network share only if you are in a certain network
# Useful for a laptop used at home and work 
# EG: You only want to mount the work Samba shares when you're on the work LAN

# Include it in your .bashrc so it runs on every login
# EG: "include /path/to/script"

INTERFACE="wlan0" #Change if needed
BROADCAST=`ifconfig $INTERFACE | grep 'Bcast:' | cut -d: -f3 | awk '{print $1}'`
TARGETLAN="<Broadcast Address of network containing the shares>" # EG: 192.168.0.255 for a /24
SERVER="<IP Of File Server>"
SHARE="<Name of Share>"
LOCATION="<Local Mount Point>" # EG: /mnt/files
USER="<Username for File Server>"
PASS="<Password for File Server>"

# Regex to check mounting will only occur in a certain network (as script should run on every startup)
# This determination is made by looking at the broadcast address of the network
if [[ $BROADCAST =~ $TARGETLAN ]]
then
    # If mount point exists
    if [ -e $LOCATION ]
    then
        mount -t cifs //$SERVER/$SHARE -o username=$USER,password=$PASS $LOCATION
    else
        # If mount point does not exist
        mkdir $LOCATION
        mount -t cifs //$SERVER/$SHARE -o username=$USER,password=$PASS $LOCATION
    fi
fi
