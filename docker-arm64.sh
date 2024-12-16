#!/bin/bash

#docker run on arm64

if [ "$2" = "" ]; then
  echo usage : docker.sh distro release
  exit
fi

docker run --rm -it --mount type=bind,src=/opt,dst=/opt --mount type=bind,src=/mnt,dst=/mnt arm64v8/$1:$2 bash
