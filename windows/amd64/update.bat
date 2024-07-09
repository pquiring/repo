@echo off

if "%1"=="update" goto update

::install wingetcreate
if not exist %LOCALAPPDATA%\Microsoft\WindowsApps\wingetcreate.exe winget install wingetcreate

for /d %%d in (manifests\p\PeterQuiring\*) do call update.bat update %%d
goto end

:update

::get github token
if exist github_token.bat call github_token.bat

wingetcreate update PeterQuiring.%~nx2 -s -t %TOKEN%

:end
