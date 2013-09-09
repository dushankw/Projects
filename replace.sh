#!/bin/bash

# Written by dushankw (http://dushan.it)
# Usage: ./replace.sh <input file> <search term> <replace term>

if [ -e $1 ]
then
    sed "s/$2/$3/g" $1 > out
    rm -rf $1 && mv out $1
else
    echo "Error: Input file does not exist"
fi
