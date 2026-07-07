#!/bin/bash

#docker run on amd64

if [ "$1" = "" ]; then
  echo usage : docker.sh distro [release]
else
  DISTRO=$1
  RELEASE=$2
  if [ $RELEASE = "" ]; then
    RELEASE=latest
  fi
  docker run --rm -it --mount type=bind,src=/opt,dst=/opt --mount type=bind,src=/mnt,dst=/mnt amd64/$DISTRO:$RELEASE bash
fi
