<?php
namespace Deployer;

require 'recipe/common.php';
require 'contrib/rsync.php';

// --- Konfiguration ---
set('application', 'hexagon');
set('shared_files', ['.env']);
set('shared_dirs', ['var/logs', 'var/cache']);
set('ssh_multiplexing', false); // Bei CI/CD oft sicherer auf false
set('ssh_options', '-o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null');

// RSYNC Konfiguration
// Das ersetzt das "git clone" auf dem Server.
set('rsync_src', __DIR__); // Das Verzeichnis in der GitHub Action
set('rsync_dest', '{{release_path}}');
set('rsync', [
    'exclude' => [
        '.git',
        '.github',
        'deploy.php',
        'tests',
        'node_modules',
        '.env.local',
        '.env',         // .env nicht hochladen, wird generiert/gelinkt
    ],
    'exclude-file' => false,
    'include' => [],
    'include-file' => false,
    'filter' => [],
    'filter-file' => false,
    'filter-perdir' => false,
    'flags' => 'rz',  // r=recursive, z=compress
    'options' => ['delete'],
    'timeout' => 300,
]);

// --- Hosts ---
host('production')
    ->set('hostname', 'playgx.de')
    ->set('remote_user', 'ssh-w01230c2')
    ->set('deploy_path', '/www/htdocs/w01230c2/mf1dd/production')
    ->set('keep_releases', 3)
    ->set('labels', ['stage' => 'production']);

host('staging')
    ->set('hostname', 'playgx.de')
    ->set('remote_user', 'ssh-w01230c2')
    ->set('deploy_path', '/www/htdocs/w01230c2/mf1dd/staging')
    ->set('keep_releases', 3)
    ->set('labels', ['stage' => 'staging']);

// --- Tasks ---

// Überschreibe den Standard-Git-Task mit Rsync
task('deploy:update_code', function () {
    invoke('rsync');
});

// Dein custom Build & Setup Prozess
task('build', function () {
    // 1. Composer auf dem Server ausführen (sicherer als lokal wegen PHP Versionen)
    run('cd {{release_path}} && composer install --prefer-dist --optimize-autoloader');
});

// --- Der Ablauf ---
desc('Deploys your project');
task('deploy', [
    'deploy:prepare',       // Legt release Ordner an
    'deploy:update_code',   // Lädt Dateien hoch (RSYNC)
    'deploy:shared',        // Symlinkt shared dirs (.env, logs)
    'deploy:writable',      // Setzt Rechte
    'build',                // Composer & Make Setup
    'deploy:symlink',       // Der magische Switch (Zero Downtime)
    'deploy:cleanup',       // Löscht alte Releases
    'deploy:success',
]);

// Falls was schief geht: Unlocken
after('deploy:failed', 'deploy:unlock');