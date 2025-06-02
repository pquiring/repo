#!/bin/bash

case $HOSTTYPE in
x86_64)
  ARCH=amd64
  ;;
aarch64)
  ARCH=aarch64
  ;;
*)
  echo Unsupported HOSTTYPE
  exit
  ;;
esac

#download JavaForce Repo file
cd /etc/apt/sources.list.d
if [ ! -f javaforce.list ]; then
  echo Download javaforce.list
  wget http://javaforce.sf.net/debian/$ARCH/javaforce.list
fi
cd /etc/apt/trusted.gpg.d
if [ ! -f javaforce.gpg ]; then
  echo Download javaforce.gpg
  wget http://javaforce.sf.net/debian/$ARCH/javaforce.gpg
fi

echo JavaForce repo installed!
