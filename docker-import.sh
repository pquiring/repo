#!/bin/bash

#import docker container

if [ "$2" = "" ]; then
  echo usage : docker.sh distro release
  exit
fi

docker import $1\_$2.tar.gz


