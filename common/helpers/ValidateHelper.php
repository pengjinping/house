<?php
namespace common\helpers;

class ValidateHelper
{

    /*
     * 添加数据与验证规则
     * @param array $data 验证数据源 [&引用传递]
     * @param array $gule 验证数据规则
     */
     public static function make(array &$data, array $gules){

        if( !is_array($data) ){
            return 'The datas is not array.';
        }

         if( !is_array($gules) ){
             return 'The gules is not array.';
         }

         foreach($gules as $name => $gule){
            if( !isset($data[$name]) ){
                return "We need to $name field in the datas";
            }

            // xss 过滤
            $data[$name] = self::remove_xss($data[$name]);
            if($error = self::valiRun($gule, $data[$name], $name) ){
                return $error;
            }
         }
     }

    /*
    * 对数据进行验证规则验证
    * @param int|string|... $data 验证数据
    * @param string $gule 验证规则字符串
    * @param string $name 验证数据键名
    * return bool
    */
    private static function valiRun($gule, $data, $name){
        // 拆分多个验证规则
        $gule = explode('|', $gule);

        foreach($gule as $method){

            @list($method, $param) = explode(':', $method);
            $valiMethod = 'vali' . ucwords($method) ;
            if( method_exists(__CLASS__, $valiMethod) ){
                if( self::$valiMethod($data, $param) ){
                    if($param){
                        return  str_replace(':attribute', $name, $param);
                    }else{
                        return "The $name field must be $method.";
                    }
                }
            }else{
                return "Method [$method] does not exist.";
            }
        }
    }

    private static function remove_xss($string){
        if( empty($string) ) {
            return $string;
        }

        if( !self::valiUrl($string) ){
            return $string;
        }

        if( strstr($string, '%25') ){
            $string = urldecode($string);
        }
        $string = urldecode($string);

        $string = preg_replace('/<script[\w\W]+?<\/script>/si', '', $string);
        $string = preg_replace("/'.*?'/si", '', preg_replace('/".*?"/si', '', $string) );

        $string = strip_tags ( trim($string) );
        $string = str_replace ( array ('"', "'", "\\", "..", "../", "./", "//", '/', '>'), '', $string );

        $string = preg_replace ( '/%0[0-8bcef]/', '', $string );
        $string = preg_replace ( '/%1[0-9a-f]/' , '', $string );
        $string = preg_replace ( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string );

        return $string;
    }


    /**********  规则验证  直接验证 *************/
    private static function valiRequired($str, $param=null){
        return empty($str);
    }
    private static function valiString($str, $param=null){
        return !is_string($str);
    }
    private function valiInt($str, $param=null){
        return $str != (int)$str;
    }
    private static function valiNumeric($str, $param=null){
        return !is_numeric($str);
    }
    private static function valiArray($str, $param=null){
        return !is_array($str);
    }
    private static function valiEmail($str, $param=null){
        return !preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $str);
    }
    private static function valiUrl($str, $param=null){
        return !preg_match("/^https?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $str);
    }
    private static function valiQq($str, $param=null){
        return !preg_match("/^[1-9]\d{5,11}$/", $str);
    }
    private static function valiZip($str, $param=null){
        return !preg_match("/^[1-9]\d{5}$/", $str);
    }
    private static function valiIdcard($str, $param=null){
        return !preg_match("/^\d{17}[X0-9]$/", $str);
    }
    private static function valiChinese($str, $param=null){
        return !ereg("^[".chr(0xa1)."-".chr(0xff)."]+$", $str);
    }
    private static function valiMobile($str, $param=null){
        return !preg_match("/^((\(\d{3}\))|(\d{3}\-))?13\d{9}$/", $str);
    }
    private static function valiPhone($str, $param=null){
        return !preg_match("/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/", $str);
    }
    private static function valiDate($str, $param=null){
        return !preg_match("/^\d{2}(\d{2})?-\d{1,2}-\d{1,2}(\s\d{1,2}:\d{1,2}(:\d{1,2})?)?$/", $str);
    }

    /**********  规则验证  组合验证 *************/
    private static function valiMin($value, &$param ){
        if( !is_numeric($param) ){
            $param = "The :attribute need to number";  return true;
        }
        if($value < $param){
            $param = "The :attribute[$value] must be more than $param"; return true;
        }
    }
    private static function valiMax($value, &$param){
        if( !is_numeric($param) ){
            $param = "The :attribute need to number"; return true;
        }
        if($value > $param){
            $param = "The :attribute[$value] must be less than $param"; return true;
        }
    }
    private static function valiSize($value, &$param){
        if( !is_numeric($param) ){
            $param = "The :attribute need to number"; return true;
        }
        if( strlen($value) != $param ){
            $param = "The :attribute[$value] length must be $param"; return true;
        }
    }
    private static function valiIn($value, &$param){
        $params = explode(',', $param);
        if( !is_array($params) || empty($param) || count($params) < 1 ){
            $param = "Validation rule in requires at least 1 parameters."; return true;
        }
        if( !in_array($value, $params) ){
            $param = "The :attribute[$value] must be one of the following: $param"; return true;
        }
    }
    private function valiBetween($value, &$param){
        @list($min, $max) = explode(',', $param);
        if( empty($min) || empty($max) ){
            $param = "Validation rule between requires at least 2 parameters."; return true;
        }
        if( $value < $min || $value > $max ){
            $param = "The :attribute[$value] must be between $min - $max."; return true;
        }
    }
    private function valiAfter($value, &$param){
        if( !$param || !preg_match("/^[\d-]{10}(\s[\d:]{8})?$/", $param) ){
            $param = "Validation rule after requires date string."; return true;
        }
        if( strtotime($value) <= strtotime($param) ){
            $param = "The :attribute[$value] must be after $param"; return true;
        }
    }
    private function valiBefore($value, &$param){
        if( !$param || !preg_match("/^[\d-]{10}(\s[\d:]{8})?$/", $param) ){
            $param = "Validation rule after requires date string."; return true;
        }
        if( strtotime($value) >= strtotime($param) ){
            $param = "The :attribute[$value] must be before $param"; return true;
        }
    }
    /******************* 规则验证 end ******************************************/
}
