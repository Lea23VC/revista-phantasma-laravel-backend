<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/Lea23VC/revista-phantasma-laravel-backend.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);


set('dotenv', '.env');

// Hosts

host('54.89.253.211')
    ->set('remote_user', 'ubuntu')
    ->set('identity_file', '~/.ssh/phantasma.pem')
    ->set('deploy_path', '~/revista-phantasma-laravel-backend');


// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});


// Hooks

after('deploy:failed', 'deploy:unlock');
