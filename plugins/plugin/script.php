<?php
namespace plugin;
/**
* @title Plugins
* @description Manage and call your plugins
* @method list List all plugins
* @method info Get plugins informations
*/
class script {

    private static function manifest($name){
        $plugin_file = 'plugins/'.$name.'/manifest.xml';
        if (file_exists($plugin_file)) {
            if($xml = simplexml_load_file($plugin_file)){
                $man_json = json_encode($xml);
                $man_array = json_decode($man_json,TRUE);
                return $man_array;
            } else {
                return false;
            }
        }
    }

    private static function scan_dir(){
        return array_diff(scandir('plugins/'), array('..', '.'));
    }

    private static function check_dependecies($name){
        if ($manifest = self::manifest($name)) {
            $dependencies=@$manifest['dependencies'];
            $plugin_list = self::scan_dir();
            $check = true;
            if (isset($dependencies)) {
                foreach ($dependencies as $name => $version) {
                    if (in_array($name, $plugin_list)) {
                        $dependace_manifest=self::manifest($name);
                        if (version_compare($version, $dependace_manifest['version']) < 0) {
                            $check = false;
                            break;
                        }
                    } else {
                        $check = false;
                        break;
                    }
                }
            }
            return $check;
        }
    }

    public static function call($category, $name, $action, $arguments=array()){
        if (file_exists('plugins/'.$name.'/'.$category.'.php')) {
            $classname = $name.'\\'.$category;
            if (self::check_dependecies($name)) {
                if (method_exists($classname,$action)) {
                    $method = new \ReflectionMethod($classname, $action);
                    $num_required = $method->getNumberOfRequiredParameters();
                    $num = $method->getNumberOfParameters();
                    $first_parameter=@$method->getParameters()[0];
                    if (is_object($first_parameter) && $first_parameter->name=='_ARRAY') {
                        return call_user_func(array($classname, $action), $arguments);
                    } else if ($num_required==count($arguments) || $num==count($arguments)) {
                        return call_user_func_array(array($classname, $action), $arguments);
                    } else {
                        \response\script::json(array(
                            'error'=>true,
                            'data'=>"Wrong number of parameters"
                        ));
                    }
                } else {
                    header("HTTP/1.1 404");
                    \response\script::json(array(
                        'error'=>true,
                        'data'=>"Action doesn't exist"
                    ));
                }
            } else {
                \response\script::json(array(
                    'error'=>true,
                    'data'=>"Dependencies not satisfied"
                ));
            }
        } else {
            \response\script::json(array(
                'error'=>true,
                'data'=>"Plugin doesn't exist"
            ));
        }
    }

    /**
    * @title Plugin list
    * @description List all plugins
    * @return array
    */
    static public function list(){
        \response\script::json(array(
            'error'=>false,
            'data'=>self::scan_dir()
        ));
    }

    /**
    * @title Plugin Informations
    * @description Get plugins informations
    * @param string Category
    * @param string Plugin
    * @param string Action
    * @return object
    */
    public static function info($category,$plugin,$action=false){
        $result=\docblockreader\script::comment($category,$plugin,$action);
        if (isset($result['param']) && !is_array($result['param'])) {
            $result['param']=array($result['param']);
        }
        if (isset($result['method']) && !is_array($result['method'])) {
            $result['method']=array($result['method']);
        }
        \response\script::json(array(
            'error'=>false,
            'data'=>$result
        ));
    }
}
