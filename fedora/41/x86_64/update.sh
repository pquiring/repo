#!/bin/bash

if [ "$1" == "" ]; then
  echo usage : update.sh {system}
  exit
fi

sudo dnf -y install createrepo rpm-sign pinentry
cp rpmmacros ~/.rpmmacros
gpg --export -a > RPM-GPG-KEY-$1
rpm --resign *.rpm
createrepo .
