<?php

namespace IMRIM\Bundle\LmsBundle;

/**
 * Description of LmsToolbox
 *
 * @author johanna
 */
class LmsToolbox {
    
    /**
     * Generate a random string with $length alphanumeric characters. 
     * @param integer $length
     * @return string 
     */
    public static function generateRandomString($length=8)
    {
        $result = "";
        $chars = 'abcdefghijklmnopqrstuvxyz0123456789';
        for ($i=0;$i<$length;$i++){
            $result = $result.$chars[mt_rand(0,strlen($chars)-1)];
        }
        return $result;
    }
}
