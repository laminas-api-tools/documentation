<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation/blob/master/LICENSE.md New BSD License
 */

$modules = array(
    'api-tools',
    'api-tools-admin',
    'api-tools-documentation',
    'api-tools-documentation-apiblueprint',
    'api-tools-documentation-swagger',
    'api-tools-doctrine',
    'api-tools-provider',
    'api-tools-welcome',
    'api-tools-api-problem',
    'api-tools-asset-manager',
    'laminas-composer-autoloading',
    'api-tools-configuration',
    'zf-console',
    'api-tools-content-negotiation',
    'api-tools-content-validation',
    'zf-deploy',
    'laminas-development-mode',
    'api-tools-doctrine-querybuilder',
    'api-tools-hal',
    'api-tools-http-cache',
    'api-tools-mvc-auth',
    'api-tools-oauth2',
    'api-tools-rest',
    'api-tools-rpc',
    'api-tools-versioning',
);

$uriTemplate  = 'https://raw.githubusercontent.com/laminas-api-tools/%s/master/README.md';
$pathTemplate = realpath(__DIR__) . '/../modules/%s.md';
$regexReplace = array(
    array('pattern' => '#\n\[\!\[build status\].*?\n#is',    'replacement' => ''),
    array('pattern' => '#\n\[\!\[coverage status\].*?\n#is', 'replacement' => ''),
    array('pattern' => '#\[(.*)\]\(((?![http|\#]).+)\)#is', 'replacement' => '[$1](https://github.com/laminas-api-tools/%s/tree/master/$2)')
);

// Set up multicall
$multiCall   = curl_multi_init();
$handles     = array();

// Add handles for all modules
foreach ($modules as $module) {
    $uri              = sprintf($uriTemplate, $module);
    $handles[$module] = curl_init($uri);
    curl_setopt($handles[$module], CURLOPT_HEADER, 0);
    curl_setopt($handles[$module], CURLOPT_RETURNTRANSFER, 1);

    curl_multi_add_handle($multiCall, $handles[$module]);
}

// Execute all handles
do {
    $result = curl_multi_exec($multiCall, $running);
} while ($running > 0);

// Get content and close handles
$results = array();
foreach ($handles as $module => $handle) {
    $results[$module] = curl_multi_getcontent($handle);
    curl_multi_remove_handle($multiCall, $handle);
    curl_close($handle);
    unset($handles[$module]);
}
curl_multi_close($multiCall);

// Pre-process markdown and write file to repository
foreach ($results as $module => $markdown) {
    foreach ($regexReplace as $info) {
        $info['replacement'] = sprintf($info['replacement'], $module);
        $markdown = preg_replace($info['pattern'], $info['replacement'], $markdown);
    }

    $path = sprintf($pathTemplate, $module);
    file_put_contents($path, $markdown);
}
