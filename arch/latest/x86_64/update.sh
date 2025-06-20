#!/bin/bash

if [ "$1" == "" ]; then
  echo usage : update.sh {system}
  exit
fi

cp ~/.gnupg/pubring.gpg ./$1.gpg
chmod 644 $1.gpg

for f in *.pkg.tar.xz; do
  gpg --detach-sign --no-armor $f
done

repo-add --verify --sign $1.db.tar.gz *.pkg.tar.xz
