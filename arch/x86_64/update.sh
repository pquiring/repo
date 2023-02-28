#!/bin/bash
for f in *.pkg.tar.xz; do
  gpg --detach-sign --no-armor $f
done

repo-add --verify --sign javaforce.db.tar.gz *.pkg.tar.xz

if [ ! -f javaforce.gpg ]; then
  cp ~/.gnupg/pubring.gpg ./javaforce.gpg
  chmod 644 javaforce.gpg
fi
