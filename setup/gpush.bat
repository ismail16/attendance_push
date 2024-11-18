@echo off
cd C:\xampp\htdocs\attendance_push
start /B /MIN cmd /C "php artisan serve"
start /B /MIN cmd /C "php artisan schedule:work"