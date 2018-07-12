<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

namespace anwaro\wincron;

class Cron
{
    const MICRO_SECONDS_FACTOR = 1000000;

    const SLEEP_TIME = 60 * Cron::MICRO_SECONDS_FACTOR;

    /** @var File $file */
    private $file;

    /** @var Job $job */
    private $job;

    /** @var int jobStartTime*/
    private $jobStartTime;

    /** @var Config $config */
    private $config;

    public function __construct($config)
    {
        $this->config = new Config($config);
        $this->job = new Job();
        $this->file = new File(
            $this->config->get('path'),
            $this->config->get('file')
        );
    }

    /**
     *
     */
    public function run()
    {
        $this->calibrateStart();
        while (true) {
            $this->startJob();
            $jobs = $this->file->getJobs();
            $this->job->run($jobs, $this->getCurrentDate());
            $this->sleep();
        }
    }

    /**
     * @return string
     */
    public function getCurrentDate()
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }

    /**
     *
     */
    public function sleep()
    {
        $delay = Cron::SLEEP_TIME - ($this->getMicroTime() - $this->jobStartTime);
        usleep($delay > 0 ? $delay : 0);
    }

    /**
     *
     */
    public function startJob()
    {
        $this->jobStartTime= $this->getMicroTime();
    }

    /**
     * @return float
     */
    public function getMicroTime()
    {
        return round(microtime(true) * Cron::MICRO_SECONDS_FACTOR);
    }

    /**
     *
     */
    public function calibrateStart()
    {
        $second = (int)(new \DateTime())->format('s');
        sleep(61 - $second);
    }

}