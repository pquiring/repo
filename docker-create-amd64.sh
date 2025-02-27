#!/bin/bash

#create docker image

if [ "$2" = "" ]; then
  echo usage : docker.sh distro release
  exit
fi

echo Creating docker image, install packages and then exit
echo Then can can use new image as $1\_$2

#docker run --name CONTAINER_ID
docker run --name $1\_$2 -it --mount type=bind,src=/opt,dst=/opt --mount type=bind,src=/mnt,dst=/mnt amd64/$1:$2 bash

#docker commit CONTAINER_ID IMAGE_ID
docker commit $1\_$2 $1\_$2

#docker rm CONTAINER_ID
docker rm $1\_$2
