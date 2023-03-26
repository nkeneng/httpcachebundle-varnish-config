<?php

$currentDir = __DIR__;

// Traverse up the directory hierarchy until we reach the project root directory
$projectRoot = dirname(dirname(dirname(dirname($currentDir))));

// Create the fos_http_cache.yaml file with some content
$fosHttpCacheYaml = <<<'YAML'
fos_http_cache:
    tags:
        enabled: true
    flash_message:
        enabled: true
    proxy_client:
        varnish:
            tag_mode: purgekeys
            http:
                servers:
                    - '%env(PURGE_SERVER)%'
                base_url: symfony.local
    user_context:
        enabled: true
        hash_cache_ttl: 900
        role_provider: true
YAML;
file_put_contents($projectRoot.'/config/packages/fos_http_cache.yaml', $fosHttpCacheYaml);

// Modify the routes.yaml file to add some content at the beginning
$routesYaml = <<<'YAML'
user_context_hash:
    path: /_fos_user_context_hash
YAML;
file_put_contents($projectRoot.'/config/routes.yaml', $routesYaml . file_get_contents($projectRoot.'/config/routes.yaml'));

// Add the PURGE_SERVER env variable to the .env file
$purgeServer = 'PURGE_SERVER=http://varnish';
file_put_contents($projectRoot.'/.env', $purgeServer . "\n", FILE_APPEND);
