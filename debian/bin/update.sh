#!/bin/bash

function update() {

  if [ "$1" == "" ]; then
    echo usage : update.sh {system}
    return
  fi

  if [ ! -f ../../bin/$1.list.input ]; then
    echo That system was not found!
    return
  fi

  rm *.gz 2>/dev/null
  rm InRelease 2>/dev/null
  rm Release 2>/dev/null
  rm Release.gpg 2>/dev/null

  cp ~/.gnupg/pubring.gpg ./$1.gpg
  chmod 644 $1.gpg

  dpkg-scanpackages . > Packages

  apt-ftparchive release . > TopRelease
  mv TopRelease Release

  gpg --clearsign -o InRelease Release
  gpg -abs -o Release.gpg Release

  #compress metadata (optional)
  #gzip InRelease
  #gzip Release
  #gzip Release.gpg
  #gzip Packages

  . /etc/os-release
  #need to remove quotes from VERSION_ID
  VERSION_ID=${VERSION_ID//\"/}
  sed 's/$(VERSION)/'$VERSION_ID'/g' < ../../bin/$1.list.input > $1.list

  echo Update complete!

}

update $1
