#!/bin/bash

git rm *.gz
git rm InRelease
git rm Release
git rm Release.gpg
git rm Packages

#delete old packages using javaforce.utils.GitRepo
ant

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

if [ ! -f javaforce.gpg ]; then
  cp ~/.gnupg/pubring.gpg ./javaforce.gpg
  chmod 644 javaforce.gpg
fi

git add *
git commit -m $(date)
git push origin --all

echo Update complete!
