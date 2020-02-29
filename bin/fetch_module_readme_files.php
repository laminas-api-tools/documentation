<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation/blob/master/LICENSE.md New BSD License
 */

$modules = array(
    'laminas-api-tools/api-tools',
    'laminas-api-tools/api-tools-admin',
    'laminas-api-tools/api-tools-documentation',
    'laminas-api-tools/api-tools-documentation-apiblueprint',
    'laminas-api-tools/api-tools-documentation-swagger',
    'laminas-api-tools/api-tools-doctrine',
    'laminas-api-tools/api-tools-provider',
    'laminas-api-tools/api-tools-welcome',
    'laminas-api-tools/api-tools-api-problem',
    'laminas-api-tools/api-tools-asset-manager',
    'laminas-api-tools/api-tools-configuration',
    'laminas-api-tools/api-tools-content-negotiation',
    'laminas-api-tools/api-tools-content-validation',
    'laminas-api-tools/api-tools-doctrine-querybuilder',
    'laminas-api-tools/api-tools-hal',
    'laminas-api-tools/api-tools-http-cache',
    'laminas-api-tools/api-tools-mvc-auth',
    'laminas-api-tools/api-tools-oauth2',
    'laminas-api-tools/api-tools-rest',
    'laminas-api-tools/api-tools-rpc',
    'laminas-api-tools/api-tools-versioning',
    'zfcampus/zf-console',
    'zfcampus/zf-deploy',
);

$uriTemplate  = 'https://raw.githubusercontent.com/%s/master/README.md';
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
    list($org, $repo) = explode('/', $module, 2);
    $uri              = sprintf($uriTemplate, $module);
    $handles[$repo]   = curl_init($uri);
    curl_setopt($handles[$repo], CURLOPT_HEADER, 0);
    curl_setopt($handles[$repo], CURLOPT_RETURNTRANSFER, 1);

    curl_multi_add_handle($multiCall, $handles[$repo]);
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
