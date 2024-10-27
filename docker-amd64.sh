#!/bin/bash

#docker run on amd64

if [ "$2" = "" ]; then
  echo usage : docker.sh distro release
  exit
fi

docker run --rm -it --mount type=bind,src=/opt,dst=/opt amd64/$1:$2 bash