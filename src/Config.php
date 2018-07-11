<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

namespace anwaro\cron;

class Config
{
    private $default = [
        'file' => 'crontab.txt',
        'path' => '',
    ];
    private $config;

    /**
     * Config constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = array_merge($this->default, $config);
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = NULL)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
}