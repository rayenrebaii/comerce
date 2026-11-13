<?php

$public = realpath(__DIR__.'/../public');
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$file = realpath($public.$uri);
if ($uri !== '/' && $file && is_file($file) && str_starts_with($file, $public.DIRECTORY_SEPARATOR)) {
    return false;
}
require $public.'/index.php';
