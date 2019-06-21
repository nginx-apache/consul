<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2018/6/26
 * Time: 14:23
 */

namespace consul\curl;

/**
 * Class Response
 * @package ucf\libs\curl
 * Author: Jiang Haiqiang
 * Email : jhq0113@163.com
 * Date: 2018/6/26
 * Time: 14:23
 */
class Response
{
    const SUCCESS = '200';

    /**
     * @var array
     */
    public $info;

    /**
     * @var string
     */
    public $body;

    /**
     * @return mixed
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:24
     */
    public function httpCode()
    {
        return $this->info['http_code'];
    }

    /**
     * @return bool
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:23
     */
    public function success()
    {
        return $this->httpCode() == self::SUCCESS;
    }

    /**
     * @return mixed
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:23
     */
    public function jsonBody2Array()
    {
        return json_decode($this->body,true);
    }

}