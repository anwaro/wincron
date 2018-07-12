<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

use PHPUnit\Framework\TestCase;
use anwaro\wincron\Job;

class JobTest extends TestCase
{

    /**
     * @group std
     */
    public function testJobShouldRun()
    {
        $job = new Job();

        $this->assertTrue($job->jobShouldRun('2018-08-01 00:05:00', '5 0 * 8 *'));
        $this->assertFalse($job->jobShouldRun('2018-07-01 00:05:00', '5 0 * 8 *'));

        $this->assertTrue($job->jobShouldRun('2018-08-01 14:15:00', '15 14 1 * *'));
        $this->assertFalse($job->jobShouldRun('2018-08-02 14:15:00', '15 14 1 * *'));

        $this->assertTrue($job->jobShouldRun('2018-07-11 22:00:00', '0 22 * * 1-5'));
        $this->assertFalse($job->jobShouldRun('2018-07-11 23:00:00', '0 22 * * 1-5'));
        $this->assertFalse($job->jobShouldRun('2018-07-15 22:00:00', '0 22 * * 1-5'));

        $this->assertTrue($job->jobShouldRun('2018-10-01 00:00:00', '0 0,12 1 */2 *'));
        $this->assertTrue($job->jobShouldRun('2018-10-01 12:00:00', '0 0,12 1 */2 *'));
        $this->assertFalse($job->jobShouldRun('2018-09-01 00:12:00', '0 0,12 1 */2 *'));
    }



    /**
     * @group std
     */
    public function testExecuteCommandFunction()
    {
        $file = 'run.log';
        $content = 'example content';
        $createFileCmd = "touch $file";
        $addContentToFile = "echo $content > $file";
        $removeFile = "rm $file";

        $job = new Job();

        $job->execute($createFileCmd);

        $this->assertTrue(file_exists($file));

        $job->execute($addContentToFile);

        $this->assertEquals(
            trim($content),
            trim(file_get_contents($file))
        );

        $job->execute($removeFile);

        $this->assertFalse(file_exists($file));
    }
}