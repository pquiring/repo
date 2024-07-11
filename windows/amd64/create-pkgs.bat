@echo off

::this script will publish new msi packages to winget-pkgs on github.com
::the process is extremely slow and error prone

::install wingetcreate
if not exist %LOCALAPPDATA%\Microsoft\WindowsApps\wingetcreate.exe winget install wingetcreate

::get github token
if exist github_token.bat call github_token.bat

for %%m in (*.msi) do wingetcreate new -t %TOKEN% http://javaforce.sourceforge.net/windows/amd64/%%m
