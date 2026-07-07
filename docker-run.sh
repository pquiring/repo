#!/bin/bash

#docker run created image

if [ "$1" = "" ]; then
  echo usage : docker.sh distro [release]
else
  DISTRO=$1
  RELEASE=$2
  if [ "$RELEASE" = "" ]; then
    RELEASE=latest
  fi
  docker run --rm -it --mount type=bind,src=/opt,dst=/opt --mount type=bind,src=/mnt,dst=/mnt $DISTRO\_$RELEASE bash
fi
