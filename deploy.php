<?php
namespace Deployer;

require 'recipe/symfony.php';

// Configuration
set('application', 'hexagon');
set('repository', 'git@github.com:username/hexagon.git');

// Hosts
host('production.example.com')
    ->set('remote_user', 'deploy')
    ->set('deploy_path', '/var/www/hexagon')
    ->set('branch', 'main')
    ->set('keep_releases', 5);

host('staging.example.com')
    ->set('remote_user', 'deploy')
    ->set('deploy_path', '/var/www/hexagon-staging')
    ->set('branch', 'develop')
    ->set('keep_releases', 3);

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
