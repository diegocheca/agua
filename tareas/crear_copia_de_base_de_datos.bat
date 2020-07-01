@echo off
echo Creando back de base de datos
cd "C:\Program Files\MySQL\MySQL Server 5.7\bin"
mysqldump -h 127.0.0.1  -uroot  ci_database > "C:\Users\DiegoC\OneDrive\back_base_datos\copia_agua"_%date:~-4,4%-%date:~-7,2%-%date:~-10,2%_%time:~0,2%-%time:~3,2%-%time:~6,2%".sql"
echo Todo bien, ya me cierro
ping localhost -n 2 >nul 
exit