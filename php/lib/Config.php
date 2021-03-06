<?php

class Config
{
    public static function getUrlPrefix()
    {
        return static::get('proto').'://'.static::get('host');
    }

    /** get config value
     * @param $v
     * @param null $default
     * @param null $config
     *
     * @return mixed|void|null
     */
    public static function get($v, $default = null, $config = null)
    {
        if ($config == null) {
            global $config;
        }
        if (is_array($v)) {
            $co = $config;
            foreach ($v as $key) {
                if (isset($co[$key]) && is_array($co)) {
                    $co = $co[$key];
                } else {
                    return;
                }
            }

            return $co;
        }

        return (isset($config[$v])) ? $config[$v] : $default;
    }
}
