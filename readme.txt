Linux/Windows repos scripts

Docker usage to generate JavaForce packages:
 - checkout JavaForce into /opt on local system
 - use docker scripts to run specific Linux version
 - cd /opt/javaforce
 - run ant in /opt/javaforce and native/linux folders
 - run package.sh to generate packages in repo folder
 - cd repo/distro...
 - run "update.sh javaforce" to generate repo files
 - copy repo files to /mnt/... to local system
 - upload packages to online repo using ftp

JavaForce .docker files
 - JavaForce includes .docker files to build distros with build tools pre-installed
