<?php
/**
 * This file is part of keenly from.
 * @author brain_yang<qiaopi520@qq.com>
 * (c) brain_yang
 * github: https://github.com/keenlysoft/
 * @time 2017年7月27日
 * For the full copyright and license information, please view the LICENSE
 */
namespace keenly\base;

use keenly;
use keenly\config;
/**
 * keenly 
 * @property \keenly\request\request $request The request component. This property is read-only.
 * @property \database\redis\redis $redis The request component. This property is read-only.
 * @property \keenly\routes\url $url The url component. This property is read-only.
 * @author jack_yang <463247339@qq.com>
 *
 */
class baseApp{
 
    use Singleton;
    
    private $reload_name;
    
    public function __construct(){
        keenly::$box = $this;
    }
    
    
    public function _int(){
        keenly::$box = $this;
    }
    
    
    public function __get($name){
        if(isset($name)){
            $this->reload_name = $name;
            $resource = $this->createObject($this->getClass($name));
            $this->_int();
            return $resource;
        }
    }
    
    
    private function getClass($name){
        $componts = $this->coreComponents();
        return isset($componts[$name])?$componts[$name]:null;
    }
    
    
    private function createObject($name){
        if(class_exists($name["class"])){
           $object = 'object'.rand(1,50);
           $this->$object =  $name["class"]::I();
           switch ($this->reload_name)
           {
               case 'redis':
                   
                   return $this->$object->redis;
               break;
               default:
                   return $this->$object;
           }
           
        }
        return false;
    }
    
    /**
     * @name loading Class Type of container
     * @return string[][]
     * @author <jack_yang.>
     */
    public function coreComponents()
    {
       $config_used = config::reload('config')->Get('used_ioc');
       $use_load = [
            'request' => ['class' => 'keenly\request\request'],
            'url'     =>  ['class' => 'keenly\routes\url'],
            'config'  => ['class' => 'keenly\config'],
            'redis'   => ['class' => 'database\redis\redis']
        ];
       return array_merge($use_load,(isset($config_used)?$config_used:[]));
    }
    
}