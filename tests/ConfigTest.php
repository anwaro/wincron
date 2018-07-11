<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

use PHPUnit\Framework\TestCase;
use anwaro\wincron\Config;

class ConfigTest extends TestCase
{
    public function testDefaultConfig()
    {
        $config = new Config([]);

        $this->assertEquals(
            'crontab.txt',
            $config->get('file')
        );

        $this->assertEquals(
            '',
            $config->get('path')
        );

    }

    public function testConfigSettingByConstructor()
    {
        $config = new Config([
            'file' => 'sample.file',
            'path' => __DIR__
        ]);

        $this->assertEquals(
            'sample.file',
            $config->get('file')
        );

        $this->assertNotEquals(
            'crontab.txt',
            $config->get('file')
        );

        $this->assertEquals(
            __DIR__,
            $config->get('path')
        );

        $this->assertNotEquals(
            '',
            $config->get('path')
        );
    }

    public function testConfigSetter()
    {
        $config = new Config([
            'file' => 'sample.file',
            'path' => __DIR__
        ]);

        $this->assertEquals(
            'sample.file',
            $config->get('file')
        );

        $config->set('file', 'crontab.txt');

        $this->assertEquals(
            'crontab.txt',
            $config->get('file')
        );
    }

}