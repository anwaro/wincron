<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

use PHPUnit\Framework\TestCase;
use anwaro\wincron\Cron;

class CronTest extends TestCase
{

    /**
     * @group std
     */
    public function testGettingCurrentTime()
    {
        $job = new Cron([]);

        $this->assertEquals(
            (new \DateTime())->format('Y-m-d H:i:s'),
            $job->getCurrentDate()
        );
    }

    /**
     * @group sleep
     */
    public function testCalibrateLoopStartTime()
    {
        $job = new Cron([]);
        $date = new DateTime();
        $job->calibrateStart();
        $this->assertEquals(
            (new \DateTime())->format('Y-m-d H:i:s'),
            $date->modify('+1 minute')->format('Y-m-d H:i:01')
        );
    }


    /**
     * @group sleep
     */
    public function testSleep()
    {
        $job = new Cron([]);
        $date = new DateTime();
        $job->startJob();
        $job->sleep();
        $this->assertEquals(
            (new \DateTime())->format('Y-m-d H:i:s'),
            $date->modify('+1 minute')->format('Y-m-d H:i:s')
        );
    }

}