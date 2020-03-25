<?php
namespace tools\node;
use think\facade\Request;
/**
 * 扫描目录
 * 根据注解实现菜单
 */
final class Scandir{

    /**
     * 忽略控制名的前缀
     * @var array
     */
    private  $ignoreController = [];

    /**
     * 忽略控制的方法名
     * @var array
     */
    private  $ignoreAction = [
        '_', '__construct', 'registermiddleware', '__debuginfo', 'initialize', 'success','login','register', 'error', 'fetch',
        'servicereport','userinfo','notify','mindoc'
    ];

    /**
     * 私有静态属性用以保存对象
     */
    private static $instance; 

    /**
     * 私有属性的构造方法 防止被 new
     */
    private function __construct ($ignoreController,$ignoreAction)
    {
        $this->ignoreController = $ignoreController;
        $this->ignoreAction = $ignoreAction;
    }
  
    /**
     * 私有属性的克隆方法 防止被克隆
     */
    private function __clone (){}
    
    /**
     * 静态方法 用以实例化调用
     */
    static public function instance($ignoreController,$ignoreAction)
    {
        if (!self::$instance instanceof self) 
        {
             self::$instance = new self($ignoreController,$ignoreAction);
        }
        return self::$instance;
    }
  

    /**
     * 获取当前访问节点
     * @return string
     */
    public static function current()
    {
        return self::parseString(Request::module() . '-' . Request::controller() . '-' . Request::action());
    }

    /**
     * 获取授权节点列表
     * @param array $checkeds
     * @param $scanModule
     * @return array
     * @throws \ReflectionException
     */
    public static function getAuthTree($scanModule,$checkeds = [])
    {
        static $nodes = [];
        if (count($nodes) > 0) return $nodes;
        //获取方法节点信息
        $methodNode  = self::getAuthList($scanModule);
        foreach ( $methodNode as $node => $value) {
            $pnode = substr($node, 0, strripos($node, '/'));
            $class = str_replace('/','-',$node);
            $nodes[$node] = ['node' => $node, 'class' => $class, 'title' => $value['title'], 'pnode' => $pnode,'menu' => $value['menu'], 'checked' => in_array($node, $checkeds)];
        }
        $nodesNew = [];
        $classNode = self::getClassList($scanModule);
        $node_key = array_keys($nodes);
        foreach ( $classNode as $item => $value)
        {
            $key = intval($item);
            $pnode = array_keys($value)[0];
            $nodesNew[$key]['sort'] = $key;
            $nodesNew[$key]['node'] = $pnode;
            $nodesNew[$key]['class'] = str_replace('/','-',$pnode);
            $nodesNew[$key]['title'] = $value[$pnode];
            foreach( $node_key as $keys => $val){
                if (stripos($val,$pnode) !== false) {
                    $nodesNew[$key]['_sub_'][] = $nodes[$val];
                }
            }
        }
        return $nodesNew;
    }

    /**
     * 获取授权节点列表
     * @param $scanModule
     * @return array
     * @throws \ReflectionException
     */
    public static function getAuthList($scanModule)
    {
        static $nodes = [];
        if (count($nodes) > 0) return $nodes;
        foreach (self::getMethodList($scanModule) as $key => $node) {
            $nodes[$key]['title'] = $node['title'];
            $nodes[$key]['menu']  = $node['menu'];
        }
        return $nodes;
    }

    /**
     * 获取方法节点列表
     * @param $scanModule
     * @return array
     * @throws \ReflectionException
     */
    public static function getMethodList($scanModule)
    {
        static $nodes = [];
        if (count($nodes) > 0) return $nodes;
        self::eachController(function (\ReflectionClass $reflection, $prenode) use (&$nodes) {
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $action = strtolower($method->getName());
                list($node, $comment) = ["{$prenode}{$action}", preg_replace("/\s/", '', $method->getDocComment())];
                
                if(in_array($action,self::$ignoreAction)){
                    break; //过滤父类控制器方法，存在就跳过，执行下一次循环
                }
                $nodes[$node] = [
                    'auth'  => stripos($comment, '@authtrue') !== false,
                    'menu'  => stripos($comment, '@menutrue') !== false,
                    'title' => preg_replace('/^\/\*\*\*(.*?)\*.*?$/', '$1', $comment),
                ];
                
                if (stripos($nodes[$node]['title'], '@') !== false) $nodes[$node]['title'] = '';
            }
        },$scanModule);
        return $nodes;
    }

    /**
     * 获取控制器节点列表
     * @param $scanModule
     * @return array
     * @throws \ReflectionException
     */
    public static function getClassList($scanModule)
    {
        static $nodes = [];
        if (count($nodes) > 0) return $nodes;
        self::eachController(function (\ReflectionClass $reflection, $prenode) use (&$nodes) {
           
            list($node, $comment) = [trim($prenode, ' / '), $reflection->getDocComment()];
            //根据sortMenu，获取菜单id
            if(preg_match('/(sortMenu=)(\d.?)/',$comment,$matches)){
                $sort = $matches['2'];
            }
            $node_menu = preg_replace('/^\/\*\*\*(.*?)\*.*?$/', '$1', preg_replace("/\s/", '', $comment));
            $nodes[$sort][$node] = $node_menu;
            if (stripos($nodes[$sort][$node], '@') !== false) $nodes[$sort][$node] = '';
        },$scanModule);
        ksort($nodes); //根据sortMenu递增排序
        unset($nodes[0]);
        return $nodes;
    }

    /**
     * 控制器扫描回调
     * @param $callable
     * @param $scanModule
     * @throws \ReflectionException
     */
    public static function eachController($callable,$scanModule)
    {
        foreach (self::scanPath($scanModule) as $file) {

            if (!preg_match("|/(\w+)/controller/(.+)\.php$|", $file, $matches)) continue;

            list($module, $controller) = [$matches[1], strtr($matches[2], '/', '.')];

            $class = substr(strtr(env('app_namespace') . $matches[0], '/', '\\'), 0, -4);
            
            if (class_exists($class) && !in_array($class,self::$ignoreController) ) {
                call_user_func($callable, new \ReflectionClass($class), self::parseString("{$module}/{$controller}/"));
            }
        }
    }

    /**
     * 驼峰转下划线规则
     * @param string $node 节点名称
     * @return string
     */
    public static function parseString($node)
    {
        if (count($nodes = explode('/', $node)) > 1) {
            $dots = [];
            foreach (explode('.', $nodes[1]) as $dot) {
                $dots[] = trim(preg_replace("/[A-Z]/", "_\\0", $dot), "_");
            }
            $nodes[1] = join('.', $dots);
        }
        return strtolower(join('/', $nodes));
    }

    /**
     * 遍历获取所有PHP文件列表
     * @param string $dirname 扫描目录
     * @param array $data 额外数据
     * @param string $ext 有文件后缀
     * @return array
     */
    private static function scanPath($dirname, $data = [], $ext = 'php')
    {
        foreach (glob("{$dirname}*") as $file) {
            if (is_dir($file)) {
                $data = array_merge($data, self::scanPath("{$file}/"));
            } elseif (is_file($file) && pathinfo($file, 4) === $ext) {
                $data[] = str_replace('\\', '/', $file);
            }
        }
        return $data;
    }
}