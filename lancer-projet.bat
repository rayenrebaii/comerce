@echo off
setlocal EnableExtensions DisableDelayedExpansion
title Stage2024 - Boutique
cd /d "%~dp0"
if errorlevel 1 exit /b 1

if /i "%~1"=="--check" goto select_php
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0scripts\install-tools.ps1"
if errorlevel 1 goto fail
call "%~dp0runtime\tools.cmd"

:select_php

if defined PHP_BIN goto php_selected
call :try_php "%~dp0runtime\php\php.exe"
for /f "delims=" %%P in ('where php.exe 2^>nul') do call :try_php "%%P"
for /d %%D in ("C:\laragon\bin\php\*") do call :try_php "%%~D\php.exe"
call :try_php "C:\xampp\php\php.exe"
if not defined PHP_BIN (
    echo [ERREUR] PHP 8.1 minimum requis. Utilisez l'archive de partage avec PHP inclus.
    goto fail
)

:php_selected
for %%P in ("%PHP_BIN%") do set "STAGE_PHP_DIR=%%~dpP"
set "PATH=%STAGE_PHP_DIR%;%PATH%"
set "PHP_ARGS="
"%PHP_BIN%" -r "exit(PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 90000 ? 0 : 1);" >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] PHP_BIN doit designer PHP 8.1 minimum, version 8.x.
    goto fail
)
"%PHP_BIN%" -r "exit(extension_loaded('pdo_sqlite') ? 0 : 1);"
if errorlevel 1 set "PHP_ARGS=-d extension=pdo_sqlite"
set "APP_ENV=portable"
set "APP_CONFIG_CACHE=%~dp0storage\framework\cache\portable-config.php"
set "APP_ROUTES_CACHE=%~dp0storage\framework\cache\portable-routes.php"
echo PHP utilise : %PHP_BIN%
if /i "%~1"=="--check" goto check

if exist vendor\autoload.php goto assets
if defined COMPOSER_PHAR goto local_composer
where composer >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Dependances absentes. Utilisez l'archive de partage ou installez Composer.
    goto fail
)
call composer install --no-interaction
if errorlevel 1 goto fail
goto assets

:local_composer
"%PHP_BIN%" %PHP_ARGS% "%COMPOSER_PHAR%" install --no-interaction
if errorlevel 1 goto fail

:assets
if exist public\build\manifest.json goto prepare
where npm.cmd >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Fichiers du site absents. Utilisez l'archive de partage ou installez Node.js.
    goto fail
)
if exist node_modules\.bin\vite.cmd goto build
call npm.cmd ci
if errorlevel 1 goto fail
:build
call npm.cmd run build
if errorlevel 1 goto fail

:prepare
"%PHP_BIN%" %PHP_ARGS% scripts\prepare-local.php
if errorlevel 1 goto fail
"%PHP_BIN%" %PHP_ARGS% artisan migrate --env=portable --force --no-interaction
if errorlevel 1 goto fail
"%PHP_BIN%" %PHP_ARGS% artisan db:seed --class=DemoSeeder --env=portable --force --no-interaction
if errorlevel 1 goto fail
if /i "%~1"=="--prepare" exit /b 0

echo.
echo Application : http://127.0.0.1:8000
echo Admin : admin@example.test / Demo2024!
echo Client : client@example.test / Demo2024!
echo Pour arreter l'application : Ctrl+C, puis fermez cette fenetre.
if /i not "%~1"=="--no-browser" start "" /min powershell.exe -NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File "%~dp0scripts\open-browser.ps1"
"%PHP_BIN%" %PHP_ARGS% -S 127.0.0.1:8000 -t public scripts\server.php
if errorlevel 1 goto fail
exit /b 0

:check
"%PHP_BIN%" %PHP_ARGS% scripts\prepare-local.php --check
if errorlevel 1 goto fail
"%PHP_BIN%" %PHP_ARGS% artisan migrate:status --env=portable --no-interaction
if errorlevel 1 goto fail
exit /b 0

:try_php
if defined PHP_BIN exit /b 0
if not exist "%~1" exit /b 0
"%~1" -r "exit(PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 90000 ? 0 : 1);" >nul 2>&1
if not errorlevel 1 set "PHP_BIN=%~1"
exit /b 0

:fail
echo.
echo Lancement interrompu. Consultez DEMARRAGE.md.
if /i "%~1"=="--check" exit /b 1
if /i "%~1"=="--prepare" exit /b 1
if /i "%~1"=="--no-browser" exit /b 1
pause
exit /b 1
