#!/bin/bash

sudo apt --yes install dpkg-dev apt-utils

rm *.gz 2>/dev/null
rm InRelease 2>/dev/null
rm Release 2>/dev/null
rm Release.gpg 2>/dev/null

cp ~/.gnupg/pubring.gpg ./javaforce.gpg
chmod 644 javaforce.gpg

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
sed 's/$(VERSION)/'$VERSION_CODENAME'/g' < javaforce.list.input > javaforce.list

echo Update complete!
