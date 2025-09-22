This is the JavaForce Debian AMD64 Repository.

You will need to install dpkg-dev package:
  sudo apt install dpkg-dev

Run gpg to create the key to sign the packages (Note:gnupg 2.1+ generates kbx files unless pubring.gpg already exists)
  mkdir ~/.gnupg
  chmod 700 ~/.gnupg
  touch ~/.gnupg/pubring.gpg
  gpg --gen-key
Backup the ~/.gnupg/*.gpg files (pubring.gpg and secring.gpg)

To update the repo after new packages are added:
  update.sh javaforce

To use the repo:
   copy javaforce.list to /etc/apt/sources.list.d
   copy javaforce.gpg to /etc/apt/trusted.gpg.d
This is performed by the iso creation scripts or see install.sh.

URL Format for sources.list (https://wiki.debian.org/DebianRepository/Format):
  $ARCH = architecture (amd64 or arm64)
