Linux/Windows repos scripts

Docker usage to generate JavaForce packages:
 - checkout JavaForce into /opt on local system
 - use docker scripts to run specific Linux version
 - cd /opt/javaforce
 - run deps.sh and ant in /opt/javaforce and native/linux folders
 - run package.sh to generate packages in repo folder
 - cd repo/distro...
 - run update.sh and upload to online repo via ftp

JavaForce .docker files
 - JavaForce includes .docker files to build distros with build tools pre-installed
 - if used the deps.sh in steps above can be skipped
