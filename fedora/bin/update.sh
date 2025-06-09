#!/bin/bash

function update() {

  if [ "$1" == "" ]; then
    echo usage : update.sh {system}
    return
  fi

  if [ ! -f ../../bin/$1.repo ]; then
    echo That system was not found!
    return
  fi

  sudo dnf -y install createrepo rpm-sign pinentry
  cp ../../bin/$1.repo .
  cp ../../bin/rpmmacros ~/.rpmmacros
  gpg --export -a > RPM-GPG-KEY-$1
  rpm --resign *.rpm
  createrepo .

}

update $1
