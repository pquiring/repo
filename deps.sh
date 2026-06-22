#!/bin/bash

# install linux deps for updating repos

function detectos {
  if [ ! -f /etc/os-release ]; then
    echo Unable to detect os
    echo /etc/os-release not found!
    exit
  fi
  . /etc/os-release
  #remove "quotes"
  ID=${ID//\"/}
  VERSION_ID=${VERSION_ID//\"/}
  case $ID in
    debian | ubuntu)
      OS=debian
      ;;
    fedora | centos)
      OS=fedora
      ;;
    arch)
      OS=arch
      ;;
    freebsd)
      OS=freebsd
      ;;
    *)
      echo Unknown os detected!
      echo ID=$ID
      ;;
  esac
}

#check if root
if [[ $EUID -ne 0 ]]; then
  echo "This script must be run as root"
  exit 1
fi

function debian {
  apt update
  xargs -d '\n' apt --yes install < deb-build.deps
}

function fedora {
  xargs -d '\n' dnf -y install < rpm-build.deps
}

function arch {
  echo no deps
}

function freebsd {
  echo no deps
}

detectos

case $OS in
  debian)
    debian
    ;;
  fedora)
    fedora
    ;;
  arch)
    arch
    ;;
  freebsd)
    freebsd
    ;;
  *)
    echo OS installation not available yet!
    echo OS=$OS
    ;;
esac

echo Deps install complete!
