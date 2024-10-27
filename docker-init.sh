#!/bin/bash

#install docker
apt install docker.io

#install qemu
apt install qemu-system-arm binfmt-support qemu-user-static

#setup docker to use qemu
docker run --rm --privileged multiarch/qemu-user-static --reset -p yes
