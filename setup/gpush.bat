@echo off
cd C:\SHR
start /B /MIN cmd /C "php artisan serve"
start /B /MIN cmd /C "php artisan schedule:work"