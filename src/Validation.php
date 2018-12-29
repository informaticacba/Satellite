<?php

namespace Satellite;

use DateTime;

class Validation
{
    public static $rule = array(
        'require' => '必須です。',
        'datetime' => '不正な日時です。',
        'max' => '文字が長すぎます。'
    );

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Create error messages from array
     * @param array $data
     * @param array $validations
     * @return array error data
     */
    public static function validate($data, $validations){
        $error = array();
        foreach($validations as $key => $rules){
            foreach($rules as $rule){
                if ($rule == 'require') {
                    if (!array_key_exists($key, $data) || $data[$key] == null) {
                        $text = Validation::$rule['require'];
                        $error[$key] = $text;
                    }
                }else if(preg_match('/^datetime\|./', $rule)){
                    $exp = explode('datetime|', $rule);
                    if(!Validation::validateDate($data[$key], $exp[1])){
                        $text = Validation::$rule['datetime'];
                        $error[$key] = $text;
                    }
                }else if(preg_match('/^max\|./', $rule)){
                    $exp = explode('max|', $rule);
                    if(mb_strlen($data[$key], 'UTF-8') > $exp[1]){
                        $text = Validation::$rule['max'];
                        $error[$key] = $text;
                    }
                }
            }
        }
        return $error;
    }
}
