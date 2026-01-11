<?php
namespace Deployer;

require 'recipe/common.php';


// Configuration
set('application', 'hexagon');
set('repository', 'https://github.com/mf1dd/hexagon.git');

// Global SSH configuration
set('ssh_multiplexing', false);
set('ssh_options', '-o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null');

// Hosts
host('playgx.de')
    ->set('remote_user', 'ssh-w01230c2')
    ->set('deploy_path', '/www/htdocs/w01230c2/mf1dd/production')
    ->set('branch', 'main')
    ->set('keep_releases', 3);

host('playgx.de')
    ->set('remote_user', 'ssh-w01230c2')
    ->set('deploy_path', '/www/htdocs/w01230c2/mf1dd/staging')
    ->set('branch', 'stage')
    ->set('keep_releases', 3);

// Tasks
task('build', function () {
    run('cd {{release_path}} && composer install --no-dev --optimize-autoloader');
    run('cd {{release_path}} && make setup');
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

// Cleanup task
task('cleanup', function () {
    $releases = get('releases_list');
    $keep = get('keep_releases', 3);
    
    if (count($releases) > $keep) {
        $toRemove = array_slice($releases, $keep);
        foreach ($toRemove as $release) {
            run("rm -rf {{deploy_path}}/releases/$release");
        }
    }
});

// Remove any automatic deployment hooks
// after('deploy:success', function () {
//     info('Deployment successful!');
// });
