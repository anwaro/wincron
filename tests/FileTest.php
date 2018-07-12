<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

use PHPUnit\Framework\TestCase;
use anwaro\wincron\File;

class FileTest extends TestCase
{

    /**
     * @group std
     */
    public function testStringIsCronComment()
    {
        $file = new File(__DIR__, '');
        $this->assertTrue(
            $file->isComment("# this is comment"),
            "Check string is comment"
        );

        $this->assertTrue(
            $file->isComment(" # this is comment with space on start"),
            "Check string with space on start is comment "
        );

        $this->assertFalse(
            $file->isComment("this is not comment"),
            "Check string is not comment"
        );
    }


    /**
     * @group std
     */
    public function testExtractPeriodFromCronEntry()
    {
        $file = new File(__DIR__, '');

        $this->assertEquals(
            '*/5 * * * *',
            $file->extractPeriod('*/5 * * * * php cron.php')
        );

        $this->assertEquals(
            '20,40 * * * *',
            $file->extractPeriod('20,40 * * * * php cron.php')
        );

        $this->assertFalse(
            $file->extractPeriod('  * * * php cron.php')
        );
    }


    /**
     * @group std
     */
    public function testExtractCommandFromCronEntry()
    {
        $file = new File(__DIR__, '');

        $this->assertEquals(
            'php cron.php',
            $file->extractCommand('*/5 * * * *', '*/5 * * * * php cron.php')
        );

        $this->assertFalse(
            $file->extractCommand('*/5 * * * *', '20,40 * * * * php cron.php')
        );

        $this->assertFalse(
            $file->extractCommand(false, '  * * * php cron.php')
        );
    }


    /**
     * @group std
     */
    public function testExtractCronJobDataFromCronEntry()
    {
        $file = new File(__DIR__, '');

        $this->assertEquals(
            [
                'period' => '*/5 * * * *',
                'cmd' => 'php cron.php'
            ],
            $file->lineToJob('*/5 * * * * php cron.php')
        );

        $this->assertFalse(
            $file->lineToJob('* * * * php cron.php')
        );
    }


    /**
     * @group std
     */
    public function testParseCronJobsFromString()
    {
        $file = new File(__DIR__, '');

        $this->assertEquals(
            [
                [
                    'period' => '*/5 * * * *',
                    'cmd' => 'php cron.php'
                ],
            ],
            $file->parseContent("# comment line \n*/5 * * * * php cron.php")
        );

        $this->assertEquals(
            [
                [
                    'period' => '*/5 * * * *',
                    'cmd' => 'php cron.php'
                ],
                [
                    'period' => '20,40 * * * *',
                    'cmd' => 'php run.php'
                ],
            ],
            $file->parseContent("# comment line \n*/5 * * * * php cron.php \n20,40 * * * * php run.php")
        );


        $this->assertEquals(
            [],
            $file->parseContent('#only comment')
        );


        $this->assertEquals(
            [],
            $file->parseContent('')
        );

    }

    /**
     * @group std
     */
    public function testReadFromCronTabFile()
    {
        $file = 'crontab.txt';
        $dir = sys_get_temp_dir();
        $content = 'Example content';

        file_put_contents(rtrim($dir) . "/$file", $content);

        $file = new File($dir, $file);

        $this->assertEquals(
            $content,
            $file->read()
        );
    }

    /**
     * @group std
     */
    public function testReadCronFromFile()
    {
        $file = 'crontab.txt';
        $dir = sys_get_temp_dir();
        $content = "# comment line \n*/5 * * * * php cron.php \n20,40 * * * * php run.php";
        $cronJob = [
            [
                'period' => '*/5 * * * *',
                'cmd' => 'php cron.php'
            ],
            [
                'period' => '20,40 * * * *',
                'cmd' => 'php run.php'
            ],
        ];

        file_put_contents(rtrim($dir) . "/$file", $content);

        $file = new File($dir, $file);

        $this->assertEquals(
            $cronJob,
            $file->getJobs()
        );
    }

}