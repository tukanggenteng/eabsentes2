@echo off
title This is your first batch script!
cd c:\laragon\www\eabsen2
C:\laragon\bin\php\php-7.1.1-Win32-VC14-x86\php.exe artisan schedule:run 1>> NUL 2>&1
echo Welcome to batch scripting!
pause