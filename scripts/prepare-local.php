<?php

// Standalone bootstrap: never reads or overwrites the owner's .env / MySQL data.
chdir(dirname(__DIR__));
$check = in_array('--check', $argv, true);
foreach (['pdo_sqlite', 'mbstring', 'openssl', 'fileinfo'] as $extension) {
    if (!extension_loaded($extension)) {
        fwrite(STDERR, "[ERREUR] Extension PHP manquante : $extension".PHP_EOL);
        exit(1);
    }
}
$database = str_replace('\\', '/', getcwd()).'/database/portable.sqlite';
if ($check) {
    foreach (['.env.portable', 'database/portable.sqlite', 'public/build/manifest.json'] as $file) {
        if (!is_file($file)) {
            fwrite(STDERR, "[ERREUR] $file absent. Lancez sans --check pour installer.".PHP_EOL);
            exit(1);
        }
    }
    echo 'Fichiers et extensions : OK'.PHP_EOL;
    exit(0);
}
foreach (['storage/app/public', 'storage/framework/cache/data', 'storage/framework/sessions',
    'storage/framework/views', 'storage/logs', 'bootstrap/cache'] as $directory) {
    if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
        throw new RuntimeException("Creation impossible : $directory");
    }
}
if (!is_file('.env.portable')) {
    $key = 'base64:'.base64_encode(random_bytes(32));
    $environment = <<<ENV
APP_NAME="Boutique Demo"
APP_ENV=portable
APP_KEY=$key
APP_DEBUG=false
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE="$database"
DB_FOREIGN_KEYS=true
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
MAIL_MAILER=log
LOG_CHANNEL=single
FILESYSTEM_DISK=local
ENV;
    if (file_put_contents('.env.portable', $environment.PHP_EOL) === false) {
        throw new RuntimeException('Creation impossible : .env.portable');
    }
} else {
    // An extracted/moved copy gets its own absolute SQLite path.
    $environment = file_get_contents('.env.portable');
    $line = 'DB_DATABASE="'.$database.'"';
    $environment = preg_match('/^DB_DATABASE=/m', $environment)
        ? preg_replace_callback('/^DB_DATABASE=.*$/m', fn () => $line, $environment)
        : $environment.PHP_EOL.$line.PHP_EOL;
    if (file_put_contents('.env.portable', $environment) === false) {
        throw new RuntimeException('Mise a jour impossible : .env.portable');
    }
}
if (!is_file($database) && !touch($database)) {
    throw new RuntimeException('Creation impossible : '.$database);
}
echo 'Configuration portable prete (SQLite, sans MySQL).'.PHP_EOL;
