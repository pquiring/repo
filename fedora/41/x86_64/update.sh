#!/bin/bash
sudo dnf -y install createrepo rpm-sign pinentry
cp rpmmacros ~/.rpmmacros
gpg --export -a > RPM-GPG-KEY-javaforce
rpm --resign *.rpm
createrepo .
