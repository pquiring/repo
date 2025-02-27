#!/bin/bash

#export docker container

if [ "$2" = "" ]; then
  echo usage : docker.sh distro release
  exit
fi

docker export $1\_$2 | gzip > $1\_$2.tar.gz

