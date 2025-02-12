#!/bin/bash

cp ~/.gnupg/pubring.gpg ./javaforce.gpg
chmod 644 javaforce.gpg

for f in *.pkg.tar.xz; do
  gpg --detach-sign --no-armor $f
done

repo-add --verify --sign javaforce.db.tar.gz *.pkg.tar.xz
