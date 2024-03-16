@echo off
cd D:\laragon\www\attendance_push
start /B /MIN cmd /C "php artisan serve"
start /B /MIN cmd /C "php artisan schedule:work"
