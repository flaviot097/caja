@echo off
REM Script para ejecutar una tarea de DBeaver desde el Programador de Tareas de Windows

REM --- CONFIGURACIÓN ---
set DBEEAVER_PATH="C:\Users\Flavio\AppData\Local\DBeaver\dbeaver-cli.exe"
set TAREA_DBEAVER="actualizar_db"


REM --- EJECUCIÓN DE LA TAREA ---
echo Ejecutando la tarea "%TAREA_DBEAVER%" en DBeaver...
start "" %DBEEAVER_PATH% --executeTask "%TAREA_DBEAVER%"

echo La ejecución de la tarea "%TAREA_DBEAVER%" ha sido iniciada.
echo Puedes revisar el estado de la tarea directamente en DBeaver.

REM --- FIN DEL SCRIPT ---
exit /b 0