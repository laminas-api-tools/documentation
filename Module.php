<?php

/**
 * @see       https://github.com/laminas-api-tools/documentation for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/documentation/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/documentation/blob/master/LICENSE.md New BSD License
 */

namespace ApiToolsDocumentation;

class Module
{
    public function getConfig()
    {
        return [
            'asset_manager' => [
                'resolver_configs' => [
                    'paths' => [
                        __DIR__ . '/asset',
                    ],
                ],
            ],
            'api-tools-documentation' => [
                'path' => realpath(__DIR__),
            ],
        ];
    }
}
