<?php

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
file_put_contents('config/packages/fos_http_cache.yaml', $fosHttpCacheYaml);

// Modify the routes.yaml file to add some content at the beginning
$routesYaml = <<<'YAML'
user_context_hash:
    path: /_fos_user_context_hash
YAML;
file_put_contents('config/routes.yaml', $routesYaml . file_get_contents('config/routes.yaml'));

// Add the PURGE_SERVER env variable to the .env file
$purgeServer = 'PURGE_SERVER=http://varnish';
file_put_contents('.env', $purgeServer . "\n", FILE_APPEND);
