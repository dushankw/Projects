#!/bin/bash

##############################################################
# Script to replace all occurrences of a word in a text file #
# Written by Dushan (http://dushan.it)                       #
##############################################################

# Inputs
echo "Enter File Name:"
read fname
echo "Enter Search Term:"
read sterm
echo "Enter Replace Term:"
read rterm

# Logic
if [ -e $fname ]
then
    sed "s/$sterm/$rterm/g" $fname > out
    echo ""
    echo "Success"
    echo "Output File: out" 
    echo "Written To: `pwd`"
else
    echo ""
    echo "Error"
    echo "Input file does not exist"
fi
