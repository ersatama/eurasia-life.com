<?php

/**
 * Помогайка для хостингов на Plesk.
 * - запускаем через Планировщик задач передавая нужную команду
 * - команды install-project-dependencies и yii-migrate желательно добавить на пост git pull
 *
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */

/*/ Composer /*/
$composerSetupFileHash = '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5';
$composerSetupFileUrl = 'https://getcomposer.org/installer';
$composerPharFile = 'composer.phar';

chdir(__DIR__);

if (!isset($_SERVER['argv'][1])) {
    text('command?');
    exit;
}
$command = $_SERVER['argv'][1];

//
$composerSetupFile = __DIR__ . '/composer-setup.php';
if ($command == 'install-composer') {
    text('Command: install Composer');
    copy($composerSetupFileUrl, $composerSetupFile);
    if (hash_file('SHA384', $composerSetupFile) !== $composerSetupFileHash) {
        text('Composer installer corrupt');
        unlink($composerSetupFile);
        exit;
    }
    text('Composer installer verified');
    text('Run Composer installer');
    include $composerSetupFile;
    exit;
} elseif (file_exists($composerSetupFile)) {
    unlink($composerSetupFile);
}

//
if ($command == 'install-project-dependencies') {
    text('Command: install project dependencies');
    init_console_params('install --no-dev --prefer-dist --no-scripts --optimize-autoloader --classmap-authoritative --apcu-autoloader');
    require_once $composerPharFile;
    exit;
}

//
if ($command == 'init-yii') {
    text('Command: init yii');
    init_console_params('--env=Production --overwrite=all');
    require_once 'init';
    exit;
}

//
if ($command == 'yii-migrate') {
    text('Command: yii migrate');
    init_console_params('migrate --interactive=0');
    require_once 'yii';
    exit;
}

text(sprintf('Unknown command `%s`', $command));
exit;

function text($text)
{
    echo $text . PHP_EOL;
}

function init_console_params($params)
{
    $_SERVER['argv'] = array_merge([0 => $_SERVER['argv'][0]], explode(' ', $params));
}
