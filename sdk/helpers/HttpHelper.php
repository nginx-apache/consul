<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2019/6/21
 * Time: 17:31
 */
namespace consul\helpers;

use consul\curl\Client;
use consul\curl\Response;

/**
 * Class HttpHelper
 * @package consul\helpers
 * Author: Jiang Haiqiang
 * Email : jhq0113@163.com
 * Date: 2019/6/21
 * Time: 17:32
 */
class HttpHelper
{
    /**
     * HttpHelper constructor.
     * Date: 2019/6/21
     * Time: 17:32
     */
    private function __construct()
    {
    }
    
    /**
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/6/21
     * Time: 17:32
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
    
    /**
     * @param string $url
     * @param array  $params
     * @param string $method
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/6/21
     * Time: 17:33
     */
    public static function http($url,$params=[],$method='get')
    {
        $client = new Client();
        $client->setUrl($url);
        return call_user_func([$client,$method],$params);
    }
    
    /**
     * @param array $params
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/6/21
     * Time: 17:43
     */
    public static function request($params=[])
    {
        return static::http($params['url'],$params['params']?:[],$params['method']?:'get');
    }
}