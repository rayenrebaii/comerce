param([switch]$ForcePortable)
$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
$root = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
Set-Location -LiteralPath $root
New-Item -ItemType Directory -Path 'runtime\downloads' -Force | Out-Null
Add-Type -AssemblyName System.IO.Compression.FileSystem

function Get-VerifiedFile([string]$Uri, [string]$Path, [string]$Hash) {
    if (-not $Hash -or $Hash -notmatch '^[a-fA-F0-9]{64}$') { throw 'Empreinte SHA256 invalide.' }
    if (Test-Path -LiteralPath $Path) {
        if ((Get-FileHash -LiteralPath $Path -Algorithm SHA256).Hash -eq $Hash) { return }
    }
    Write-Host ('Telechargement : ' + $Uri)
    Invoke-WebRequest $Uri -UseBasicParsing -OutFile $Path -TimeoutSec 300
    if ((Get-FileHash -LiteralPath $Path -Algorithm SHA256).Hash -ne $Hash) {
        throw 'Le fichier telecharge ne correspond pas a son empreinte officielle.'
    }
}

$php = $null
if ($env:PHP_BIN -and -not $ForcePortable) {
    $php = (Get-Item -LiteralPath $env:PHP_BIN).FullName
} elseif (-not $ForcePortable) {
    $candidates = @((Join-Path $root 'runtime\php\php.exe'))
    $candidates += @(Get-Command php.exe -ErrorAction SilentlyContinue | ForEach-Object Source)
    $candidates += @(Get-ChildItem 'C:\laragon\bin\php\*\php.exe' -ErrorAction SilentlyContinue | ForEach-Object FullName)
    $candidates += 'C:\xampp\php\php.exe'
    foreach ($candidate in $candidates) {
        if (-not (Test-Path -LiteralPath $candidate)) { continue }
        $env:STAGE_PHP_DIR = Split-Path -Parent $candidate
        & $candidate -r 'exit(PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 80300 ? 0 : 1);' 2>$null
        if ($LASTEXITCODE -eq 0) { $php = $candidate; break }
    }
}
if (-not $php) {
    if (-not [Environment]::Is64BitOperatingSystem) { throw 'Windows 64 bits est requis.' }
    $releases = Invoke-RestMethod 'https://downloads.php.net/~windows/releases/releases.json' -TimeoutSec 60
    $release = $releases.'8.2'.'nts-vs16-x64'.zip
    if (-not $release -or $release.path -notmatch '^php-[a-zA-Z0-9.\-]+\.zip$') { throw 'Version PHP officielle introuvable.' }
    $download = Join-Path $root ('runtime\downloads\' + $release.path)
    Get-VerifiedFile ('https://downloads.php.net/~windows/releases/' + $release.path) $download $release.sha256
    $phpDirectory = Join-Path $root 'runtime\php'
    if (Test-Path -LiteralPath (Join-Path $phpDirectory 'php.exe')) {
        # Do not overwrite an existing runtime when explicitly testing a download.
        $phpDirectory = Join-Path $root ('runtime\php-' + $releases.'8.2'.version)
    }
    if (-not (Test-Path -LiteralPath (Join-Path $phpDirectory 'php.exe'))) {
        [IO.Compression.ZipFile]::ExtractToDirectory($download, $phpDirectory)
    }
    $extensions = @('mbstring', 'openssl', 'fileinfo', 'pdo_sqlite', 'sqlite3', 'curl', 'zip', 'gd')
    $ini = @('[PHP]', 'extension_dir="${STAGE_PHP_DIR}/ext"', 'date.timezone=Africa/Tunis',
        'memory_limit=512M', 'upload_max_filesize=20M', 'post_max_size=24M')
    foreach ($extension in $extensions) {
        if (Test-Path -LiteralPath (Join-Path $phpDirectory ('ext\php_' + $extension + '.dll'))) {
            $ini += 'extension=' + $extension
        }
    }
    $ini += 'curl.cainfo="${STAGE_PHP_DIR}/cacert.pem"'
    $ini += 'openssl.cafile="${STAGE_PHP_DIR}/cacert.pem"'
    [IO.File]::WriteAllLines((Join-Path $phpDirectory 'php.ini'), $ini, (New-Object Text.UTF8Encoding($false)))
    Invoke-WebRequest 'https://curl.se/ca/cacert.pem' -UseBasicParsing -OutFile (Join-Path $phpDirectory 'cacert.pem') -TimeoutSec 60
    $php = Join-Path $phpDirectory 'php.exe'
}
$env:STAGE_PHP_DIR = Split-Path -Parent $php
& $php -r 'exit(PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 80300 ? 0 : 1);'
if ($LASTEXITCODE -ne 0) { throw 'PHP ne demarre pas. Verifiez le redistribuable Microsoft Visual C++ x64.' }

$composerPhar = ''
if ((-not (Test-Path 'vendor\autoload.php')) -or $ForcePortable) {
    if ((-not (Get-Command composer -ErrorAction SilentlyContinue)) -or $ForcePortable) {
        $composerPhar = Join-Path $root 'runtime\composer.phar'
        if (-not (Test-Path -LiteralPath $composerPhar)) {
            $hash = (Invoke-WebRequest 'https://getcomposer.org/composer-stable.phar.sha256sum' -UseBasicParsing -TimeoutSec 60).Content.Trim().Split(' ')[0]
            Get-VerifiedFile 'https://getcomposer.org/composer-stable.phar' $composerPhar $hash
        }
    }
}

$nodeDirectory = ''
if ((-not (Test-Path 'public\build\manifest.json')) -or $ForcePortable) {
    $localNode = Get-ChildItem 'runtime\node-v*-win-x64\node.exe' -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($localNode) {
        $nodeDirectory = $localNode.DirectoryName
    } elseif ((-not (Get-Command npm.cmd -ErrorAction SilentlyContinue)) -or $ForcePortable) {
        $base = 'https://nodejs.org/dist/latest-v24.x/'
        $hashes = (Invoke-WebRequest ($base + 'SHASUMS256.txt') -UseBasicParsing -TimeoutSec 60).Content
        $match = [regex]::Match($hashes, '(?m)^([a-f0-9]{64})\s+(node-v[0-9.]+-win-x64\.zip)\s*$')
        if (-not $match.Success) { throw 'Archive officielle Node.js introuvable.' }
        $download = Join-Path $root ('runtime\downloads\' + $match.Groups[2].Value)
        Get-VerifiedFile ($base + $match.Groups[2].Value) $download $match.Groups[1].Value
        [IO.Compression.ZipFile]::ExtractToDirectory($download, (Join-Path $root 'runtime'))
        $nodeDirectory = Join-Path (Join-Path $root 'runtime') ([IO.Path]::GetFileNameWithoutExtension($match.Groups[2].Value))
    }
}

$cleanPath = (($env:PATH -split ';') | Where-Object { $_ -and $_ -notmatch '(?i)[\\/]oraclexe[\\/]' }) -join ';'
if ($nodeDirectory) { $cleanPath = $nodeDirectory + ';' + $cleanPath }
$commands = @('@echo off', ('set "PHP_BIN=' + $php + '"'),
    ('set "COMPOSER_PHAR=' + $composerPhar + '"'))
# Oracle XE bundles an old unzip that can hang Composer on paths with spaces.
# Limit the adjustment to this launcher's environment; do not change Windows PATH.
$commands += 'set "PATH=' + $cleanPath + '"'
[IO.File]::WriteAllLines((Join-Path $root 'runtime\tools.cmd'), $commands, [Text.Encoding]::Default)
Write-Host 'Outils de lancement prets.'
