<?php
namespace Deployer;

require 'recipe/common.php';
require 'contrib/rsync.php';

// --- Konfiguration ---
set('application', 'hexagon');
set('shared_files', ['.env']);
set('shared_dirs', ['var/logs', 'var/cache']);
set('ssh_multiplexing', false);

// RSYNC Konfiguration
// Das ersetzt das "git clone" auf dem Server.
set('rsync_src', __DIR__);
set('rsync_dest', '{{release_path}}');
set('rsync', [
    'exclude' => [
        '.git',
        '.github',
        'deploy.php',
        'tests',
        'node_modules',
        '.env.local',
        '.env',
    ],
    'exclude-file' => false,
    'include' => [],
    'include-file' => false,
    'filter' => [],
    'filter-file' => false,
    'filter-perdir' => false,
    'flags' => 'rz',
    'options' => ['delete'],
    'timeout' => 300,
]);

// --- Hosts ---
host('production')
    ->set('ssh_args', ['-o StrictHostKeyChecking=no', '-o UserKnownHostsFile=/dev/null'])
    ->set('hostname', 'playgx.de')
    ->set('remote_user', 'ssh-w01230c2')
    ->set('deploy_path', '/www/htdocs/w01230c2/mf1dd/production')
    ->set('keep_releases', 3)
    ->set('labels', ['stage' => 'production']);

host('staging')
    ->set('ssh_args', ['-o StrictHostKeyChecking=no', '-o UserKnownHostsFile=/dev/null'])
    ->set('hostname', 'playgx.de')
    ->set('remote_user', 'ssh-w01230c2')
    ->set('deploy_path', '/www/htdocs/w01230c2/mf1dd/staging')
    ->set('keep_releases', 3)
    ->set('labels', ['stage' => 'staging']);

// --- Tasks ---

task('deploy:update_code', function () {
    invoke('rsync');
});

task('build', function () {
    run('cd {{release_path}} && composer install --no-dev --optimize-autoloader');
});

task('deploy', [
    'deploy:unlock',
    'deploy:prepare',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'build',
    'deploy:symlink',
    'deploy:cleanup',
    'deploy:success',
]);

after('deploy:failed', 'deploy:unlock');