#!/bin/bash

#docker run created image

if [ "$2" = "" ]; then
  echo usage : docker.sh distro release
  exit
fi

docker run --rm -it --mount type=bind,src=/opt,dst=/opt --mount type=bind,src=/mnt,dst=/mnt $1\_$2 bash
