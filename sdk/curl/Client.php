<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2018/6/26
 * Time: 14:23
 */

namespace consul\curl;

/**
 * Class Client
 * @package ucf\libs\curl
 * @example
 * $client = new Client();
 * $client->setUrl('http://www.baidu.com');
 *
 * //如果是https请求
 * //$client->setHttps();
 *
 * //如果需要设置headers
 * // $client->setHeaders([
 *      'datetime:'.date('Y-m-d H:i:s')
 * ]);
 *
 * //如果需要设置curlopt
 * //$client->setOpt(CURLOPT_RETURNTRANSFER,1);
 *
 * //发送get请求
 * $response = $client->get();
 *
 * //发送post请求,并携带参数
 * $response = $client->post(['userName'=>'mayun']);
 *
 * //发送post请求，并携带json body
 * $response = $client->post(json_encode(['userName'=>'mayun']));
 *
 * //发送put请求，并携带json body
 * $response = $client->put(['userName'=>'mayun']);
 *
 * //发送delete请求，并携带json body
 * $response = $client->delete(['userName'=>'mayun']);
 *
 * //发送patch请求，并携带json body
 * $response = $client->patch(['userName'=>'mayun']);
 *
 * //发送header请求，并携带json body
 * $response = $client->header(['userName'=>'mayun']);
 *
 * //发送options请求，并携带json body
 * $response = $client->options(['userName'=>'mayun']);
 *
 * //判断请求是否返回200
 *
 * if($response->success()) {
 *     //200逻辑
 *     //如果响应数据为json,可以转换为数组
 *     $data = $response->jsonBody2Array();
 *
 *     //如果想直接读取body内容
 *     $body = $response->body;
 *
 *     //如果想直接读取http响应码
 *     $code = $response->httpCode();
 * }
 *
 * Author: Jiang Haiqiang
 * Email : jhq0113@163.com
 * Date: 2018/6/26
 * Time: 14:24
 */
class Client
{
    const POST      = 'post';
    const GET       = 'get';
    const PUT       = 'put';
    const DELETE    = 'delete';
    const PATCH     = 'patch';
    const HEADER    = 'header';
    const OPTIONS   = 'options';

    /**
     * @var resource
     */
    private $_handler;

    /**
     * @var string
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 15:55
     */
    private $_url;

    /**
     * Client constructor.
     * @param string $url
     * Date: 2018/6/26
     * Time: 14:24
     */
    public function __construct()
    {
        $this->_handler = curl_init();

        $this->setOpt(CURLOPT_RETURNTRANSFER,1);
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    public function setOpt($attribute,$value)
    {
        curl_setopt($this->_handler,$attribute , $value);
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    public function setOpts($attributes=[])
    {
        if(!empty($attributes)) {
            curl_setopt_array($this->_handler,$attributes);
        }
        return $this;
    }

    /**设置请求头
     * @param array $headers
     * @return $this
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    public function setHeaders($headers=[])
    {
        curl_setopt($this->_handler,CURLOPT_HTTPHEADER,$headers);
        return $this;
    }

    /**
     * @param string $url
     * @return $this
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 15:56
     */
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    /**
     * @return $this
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    public function setHttps()
    {
        $this->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $this->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $this;
    }

    /**
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    protected function _request()
    {
        if(substr($this->_url,0,5) === 'https') {
            $this->setHttps();
        }
        
        $response = new Response();
        $response->body     = curl_exec($this->_handler);
        $response->info   = curl_getinfo($this->_handler);
        curl_close($this->_handler);

        return $response;
    }

    /**
     * @param array $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    public function get($data = [])
    {
        if(!empty($data)) {
            $this->_url .= '?'.http_build_query($data);
        }

        $this->setOpt(CURLOPT_URL,$this->_url);

        $this->setOpt(CURLOPT_HTTPGET,true);
        return $this->_request();
    }

    /**
     * @param array $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 14:25
     */
    public function post($data = [])
    {
        $this->setOpt(CURLOPT_POST,true);
        if(!empty($data)) {
            $this->setOpt(CURLOPT_POSTFIELDS,is_array($data) ? http_build_query($data) : $data);
        } else {
            $this->setOpt(CURLOPT_POSTFIELDS,[]);
        }

        $this->setOpt(CURLOPT_URL,$this->_url);

        return $this->_request();
    }

    /**
     * @param mixed $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 16:46
     */
    public function put($data = [])
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST,'PUT');

        $this->setOpt(CURLOPT_POSTFIELDS,is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data);

        $this->setOpt(CURLOPT_URL,$this->_url);
        return $this->_request();
    }

    /**
     * @param array $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 16:58
     */
    public function delete($data=[])
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST,'DELETE');

        $this->setOpt(CURLOPT_POSTFIELDS,is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data);

        $this->setOpt(CURLOPT_URL,$this->_url);
        return $this->_request();
    }

    /**
     * @param array $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 17:09
     */
    public function patch($data=[])
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST,'PATCH');

        $this->setOpt(CURLOPT_POSTFIELDS,is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data);

        $this->setOpt(CURLOPT_URL,$this->_url);
        return $this->_request();
    }

    /**
     * @param array $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 17:09
     */
    public function header($data=[])
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST,'HEADER');

        $this->setOpt(CURLOPT_POSTFIELDS,is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data);

        $this->setOpt(CURLOPT_URL,$this->_url);
        return $this->_request();
    }

    /**
     * @param array $data
     * @return Response
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2018/6/26
     * Time: 17:10
     */
    public function options($data=[])
    {
        $this->setOpt(CURLOPT_CUSTOMREQUEST,'OPTIONS');

        $this->setOpt(CURLOPT_POSTFIELDS,is_array($data) ? json_encode($data,JSON_UNESCAPED_UNICODE) : $data);

        $this->setOpt(CURLOPT_URL,$this->_url);
        return $this->_request();
    }
}