<?php
namespace Deployer;

require 'recipe/symfony.php';

// Load environment variables
$env = [];
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

// Configuration
set('application', 'hexagon');
set('repository', 'git@github.com:mf1dd/hexagon.git');

// Hosts
host($env['DEPLOYER_PRODUCTION_HOST'] ?? 'production.example.com')
    ->set('remote_user', $env['DEPLOYER_REMOTE_USER'] ?? 'deploy')
    ->set('deploy_path', $env['DEPLOYER_PRODUCTION_PATH'] ?? '/var/www/hexagon')
    ->set('branch', $env['DEPLOYER_PRODUCTION_BRANCH'] ?? 'main')
    ->set('keep_releases', (int)($env['DEPLOYER_PRODUCTION_RELEASES'] ?? 5));

host($env['DEPLOYER_STAGING_HOST'] ?? 'staging.example.com')
    ->set('remote_user', $env['DEPLOYER_REMOTE_USER'] ?? 'deploy')
    ->set('deploy_path', $env['DEPLOYER_STAGING_PATH'] ?? '/var/www/hexagon-staging')
    ->set('branch', $env['DEPLOYER_STAGING_BRANCH'] ?? 'develop')
    ->set('keep_releases', (int)($env['DEPLOYER_STAGING_RELEASES'] ?? 3));

// Tasks
task('build', function () {
    run('cd {{release_path}} && composer install --no-dev --optimize-autoloader');
    run('cd {{release_path}} && npm install && npm run build');
});

task('deploy:shared_files', function () {
    // Create shared directories
    run('mkdir -p {{shared_path}}/var/logs');
    run('mkdir -p {{shared_path}}/var/cache');
    
    // Create shared files
    run('touch {{shared_path}}/.env');
});

task('deploy:writable_dirs', function () {
    run('cd {{release_path}} && chmod -R 755 var');
    run('cd {{release_path}} && chmod -R 777 var/logs var/cache');
});

task('deploy:clear_cache', function () {
    run('cd {{release_path}} && php bin/console cache:clear --env=prod');
});

// Manual deployment only - no automatic triggers
task('deploy', [
    'deploy:prepare',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'build',
    'deploy:shared_files',
    'deploy:writable_dirs',
    'deploy:clear_cache',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

// Rollback task
task('rollback', function () {
    run('cd {{deploy_path}} && {{bin/symlink}} {{previous_release}} current');
    info('Rollback to previous release successful!');
});

// Remove any automatic deployment hooks
// after('deploy:success', function () {
//     info('Deployment successful!');
// });
