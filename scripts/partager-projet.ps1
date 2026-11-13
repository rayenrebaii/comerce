param([string]$PhpPath)
$ErrorActionPreference = 'Stop'
$root = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
Set-Location -LiteralPath $root

if (-not $PhpPath) {
    $candidates = @((Join-Path $root 'runtime\php\php.exe'))
    $candidates += @(Get-ChildItem 'C:\laragon\bin\php\*\php.exe' -ErrorAction SilentlyContinue | ForEach-Object FullName)
    $candidates += @(Get-Command php.exe -ErrorAction SilentlyContinue | ForEach-Object Source)
    foreach ($candidate in $candidates) {
        if (-not (Test-Path -LiteralPath $candidate)) { continue }
        & $candidate -r 'exit(PHP_VERSION_ID >= 80100 && PHP_VERSION_ID < 90000 ? 0 : 1);' 2>$null
        if ($LASTEXITCODE -eq 0) { $PhpPath = $candidate; break }
    }
}
if (-not $PhpPath) { throw 'PHP 8.1 minimum introuvable pour le paquet Windows.' }
if (-not (Test-Path 'vendor\autoload.php')) { throw 'Lancez lancer-projet.bat --prepare avant de partager.' }
if (-not (Test-Path 'public\build\manifest.json')) { throw 'Compilez les fichiers du site avant de partager.' }

$phpDirectory = Split-Path -Parent $PhpPath
New-Item -ItemType Directory -Path (Join-Path $root 'dist') -Force | Out-Null
$archivePath = Join-Path $root ('dist\stage2024-partage-' + (Get-Date -Format 'yyyyMMdd-HHmmss') + '.zip')
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem
$stream = [IO.File]::Open($archivePath, [IO.FileMode]::CreateNew)
$zip = New-Object IO.Compression.ZipArchive($stream, [IO.Compression.ZipArchiveMode]::Create, $false)

function Add-File([string]$Source, [string]$Relative) {
    [IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $Source,
        ('stage2024/' + $Relative.Replace('\', '/')), [IO.Compression.CompressionLevel]::Optimal) | Out-Null
}
function Add-Text([string]$Relative, [string]$Content) {
    $entry = $zip.CreateEntry('stage2024/' + $Relative)
    $writer = New-Object IO.StreamWriter($entry.Open(), (New-Object Text.UTF8Encoding($false)))
    try { $writer.Write($Content) } finally { $writer.Dispose() }
}

try {
    # Include source, dependencies and compiled assets; never the owner's environment/data.
    foreach ($directory in @('app', 'config', 'resources', 'routes', 'tests', 'scripts', 'vendor', 'public')) {
        foreach ($file in Get-ChildItem -LiteralPath (Join-Path $root $directory) -File -Recurse -Force) {
            $relative = $file.FullName.Substring($root.Length + 1)
            if ($relative -match '(^|\\)(\.git|\.env[^\\]*)(\\|$)' -or $relative -eq 'public\hot' -or $relative -like 'public\storage\*') { continue }
            Add-File $file.FullName $relative
        }
    }
    foreach ($directory in @('database\migrations', 'database\seeders', 'database\factories')) {
        foreach ($file in Get-ChildItem -LiteralPath (Join-Path $root $directory) -File -Recurse -Force) {
            Add-File $file.FullName $file.FullName.Substring($root.Length + 1)
        }
    }
    foreach ($name in @('artisan', 'composer.json', 'composer.lock', 'package.json', 'package-lock.json',
        'vite.config.js', 'phpunit.xml', '.env.example', '.gitignore', '.gitattributes', '.editorconfig',
        'lancer-projet.bat', 'partager-projet.bat', 'DEMARRAGE.md', 'README.md', 'bootstrap\app.php')) {
        Add-File (Join-Path $root $name) $name
    }
    foreach ($directory in @('bootstrap/cache', 'storage/app/public', 'storage/framework/cache/data',
        'storage/framework/sessions', 'storage/framework/views', 'storage/logs')) {
        Add-Text ($directory + '/.gitignore') "*`n!.gitignore`n"
    }

    # PHP and its dependencies travel with the project. Do not copy the local php.ini.
    Add-File $PhpPath 'runtime/php/php.exe'
    foreach ($file in Get-ChildItem -LiteralPath $phpDirectory -File | Where-Object { $_.Extension -eq '.dll' -or $_.Name -match '^(license|readme|news)' }) {
        Add-File $file.FullName ('runtime/php/' + $file.Name)
    }
    foreach ($file in Get-ChildItem -LiteralPath (Join-Path $phpDirectory 'ext') -File -Filter '*.dll') {
        Add-File $file.FullName ('runtime/php/ext/' + $file.Name)
    }
    Add-Text 'runtime/php/php.ini' @'
[PHP]
extension_dir="${STAGE_PHP_DIR}/ext"
extension=mbstring
extension=openssl
extension=fileinfo
extension=pdo_sqlite
extension=sqlite3
extension=curl
extension=gd
date.timezone=Africa/Tunis
memory_limit=256M
upload_max_filesize=20M
post_max_size=24M
display_errors=Off
log_errors=On
'@
} finally {
    $zip.Dispose()
    $stream.Dispose()
}
Write-Output ('Archive prete : ' + $archivePath)
Write-Output 'Votre ami doit extraire le ZIP puis double-cliquer sur lancer-projet.bat.'
