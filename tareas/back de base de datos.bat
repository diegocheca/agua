@echo off
echo Copiando

:while
XCOPY  C:\xampp\htdocs\codeigniter\fotos\* "C:\Users\diego\Google Drive\fotos" /Y
echo Listo hacia google drive
ping localhost -n 100 >nul 
XCOPY "C:\Users\diego\Google Drive\fotos" C:\xampp\htdocs\codigo_barra\fotos\* /Y
echo Listo desde google drive
ping localhost -n 100 >nul 
goto :while
pause
exit
