@echo off
cd /D %~dp0
type tools.txt
ping -n 2 127.0.0.1 > nul
@echo:
@echo:
@echo:
@echo:
echo Apache Http server and Mysql server are starting ...
@echo off
@start /b "" mysql\bin\mysqld --defaults-file=mysql\bin\my.ini --standalone --console
@start /b "" apache\bin\httpd.exe
ping -n 2 127.0.0.1 > nul
echo Please Wait ....
cls 
echo Congratulations! Your servertool should now be running
echo and can be accessed locally from http://localhost
@echo:
echo ------------------------------------------------------------
@echo:
echo To stop your server from running, just close this window at any time.
echo Please note that this window must be kept open to run the servertool
echo this will also run the scheduled cron based tasks.
@echo:
@echo:
@echo:
@echo:

 :loop
php\php.exe htdocs\cronjobs\igcmds.php
php\php.exe htdocs\cronjobs\limiters.php
 timeout /t 30
 echo IGCMDS run
 echo Limiters run
 goto loop
