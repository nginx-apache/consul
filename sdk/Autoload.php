<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2019/5/16
 * Time: 10:54
 */
namespace consul;

/**自动加载类
 * Class Autoload
 * @package base
 * Author: Jiang Haiqiang
 * Email : jhq0113@163.com
 * Date: 2019/5/16
 * Time: 10:54
 */
class Autoload
{
    /**命名空间map
     * @var array
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/5/20
     * Time: 15:52
     */
    private static $_namespaceMap = [
        'consul' => __DIR__
    ];

    /**
     * Extension constructor.
     * Date: 2019/5/16
     * Time: 11:00
     */
    private function __construct()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/5/16
     * Time: 11:00
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**注册命名空间
     * @param string  $baseNamespace
     * @param string  $dir
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/5/20
     * Time: 15:54
     */
    public static function registerNamespace($baseNamespace,$dir)
    {
        self::$_namespaceMap[ $baseNamespace ] = $dir;
    }

    /**spl_autoload实现
     * @param string $class
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/5/20
     * Time: 16:00
     */
    public static function autoload($class)
    {
        $position = strpos($class,'\\');
        $prefix = substr($class,0,$position);

        if(!isset(self::$_namespaceMap[ $prefix ])) {
            return;
        }

        $fileName = self::$_namespaceMap[ $prefix ].str_replace('\\','/',substr($class,$position)).'.php';
        if(file_exists($fileName)) {
            require $fileName;
        }
    }

    /**启用base框架自动加载
     * Author: Jiang Haiqiang
     * Email : jhq0113@163.com
     * Date: 2019/5/20
     * Time: 16:07
     */
    public static function run()
    {
        spl_autoload_register('consul\Autoload::autoload');
    }
}