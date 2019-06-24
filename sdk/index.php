<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2019/6/21
 * Time: 17:30
 */
use consul\helpers\HttpHelper;

error_reporting(E_ERROR);

require __DIR__.'/Autoload.php';

\consul\Autoload::run();

$url = 'http://10.20.76.58:3500';

/**
 * 注册服务
 */
$response['register'][] = HttpHelper::request([
    'url'       => $url.'/v1/catalog/register',
    'params'    => [
        'Datacenter'        =>  'develop',
        'Node'              =>  'local',
        'Address'           =>  '10.20.76.58',
        'TaggedAddresses'   => [
            'lan'  => '10.20.76.58',
            'wan'  => '175.191.203.11'
        ],
        'NodeMeta'          => [
            'key'       => 'value'
        ],
        'Service'           => [
            'Id'        => '10.20.76.58:6380',
            'Service'   => 'redis',
            'tags'      => ['develop'],
            'Address'   => '10.20.76.58',
            'Port'      => 6380,
            'Meta'      =>[
                'auth'  => 'jhq0113'
            ],
        ],
        'Check' => [
            'Node'          => 'local',
            'CheckID'       => 'service:redis:10.20.76.58:6379',
            'Name'          => 'redis health check',
            'Notes'         => 'redis health check',
            'Status'        => 'passing',
            'ServiceID'     => '10.20.76.58:6379',
            'Definition'    => [
                'TCP'                               => '10.20.76.58:6379',
                'Interval'                          => '5s',
                'Timeout'                           => '1s',
                'DeregisterCriticalServiceAfter'    => '30s'
            ]
        ],
        'SkipNodeUpdate' => false
    ],
    'method'    => 'put'
])->body;

/**
 * 注册服务
 */
/**
 * 注册服务
 */
$response['register'][] = HttpHelper::request([
    'url'       => $url.'/v1/catalog/register',
    'params'    => [
        'Datacenter'        =>  'develop',
        'Node'              =>  'test1',
        'Address'           =>  '10.20.70.215',
        'TaggedAddresses'   => [
            'lan'  => '10.20.70.215',
            'wan'  => '111.203.205.32'
        ],
        'NodeMeta'          => [
            'key'       => 'value'
        ],
        'Service'           => [
            'Id'        => '10.20.70.215:6379',
            'Service'   => 'redis',
            'tags'      => ['test'],
            'Address'   => '10.20.70.215',
            'Port'      => 6379,
            'Meta'      =>[
                'auth'  => '123456'
            ],
        ],
        'Check' => [
            'Node'          => 'test1',
            'CheckID'       => 'service:redis:10.20.70.215:6379',
            'Name'          => 'redis health check',
            'Notes'         => 'redis health check',
            'Status'        => 'passing',
            'ServiceID'     => '10.20.70.215:6379',
            'Definition'    => [
                'TCP'                               => '10.20.70.215:6379',
                'Interval'                          => '5s',
                'Timeout'                           => '1s',
                'DeregisterCriticalServiceAfter'    => '30s'
            ]
        ],
        'SkipNodeUpdate' => false
    ],
    'method'    => 'put'
])->body;

/**
 * 发现服务
 */
/*$response['discover']['before'] = HttpHelper::request([
    'url' => $url.'/v1/catalog/service/redis'
])->body;*/

/**
 * 摘除服务
 */
/*$response['deregister'] = HttpHelper::request([
    'url'       => $url.'/v1/catalog/deregister',
    'params'    => [
        'Datacenter'    =>  'develop',
        'Node'          =>  'redis',
        'ServiceID'     =>  '10.20.70.215:6379',
    ],
    'method'  => 'put'
])->body;

$response['deregister'] = HttpHelper::request([
    'url'       => $url.'/v1/catalog/deregister',
    'params'    => [
        'Datacenter'    =>  'develop',
        'Node'          =>  'redis',
        'ServiceID'     =>  '10.20.76.58:6379',
    ],
    'method'  => 'put'
])->body;*/


/**
 * 发现服务
 */
$response['discover']['after'] = HttpHelper::request([
    'url' => $url.'/v1/catalog/service/redis'
])->body;

echo $response['discover']['after'];