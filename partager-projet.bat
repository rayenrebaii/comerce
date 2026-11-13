@echo off
setlocal
cd /d "%~dp0"
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\partager-projet.ps1"
if errorlevel 1 (
    echo Echec de creation de l'archive.
    pause
    exit /b 1
)
pause
