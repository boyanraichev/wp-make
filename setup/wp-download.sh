#!/bin/bash

#Download the latest copy of WordPress into a directory, grab all the files in the new /wordpress folder
#Put all the files in the current directory, remove the now empty /wordpress directory
#Remove the tarball 

#download latest wordpress with CURL
curl -OL http://wordpress.org/latest.tar.gz

#De-compress the tarball
tar -zxvf latest.tar.gz

#Copy everything from the new WordPress Directory into current directory
cp -rvf wordpress/* .

#Remove the wordpress folder
rm -R wordpress

#remove the tarball
rm latest.tar.gz

