<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

namespace anwaro\wincron;

class Job
{

    /**
     * @param $jobs
     * @param $date
     */
    public function run($jobs, $date)
    {
        foreach ($jobs as $job) {
            if ($this->jobShouldRun($date, $job['period'])) {
                $this->execute($job['cmd']);
            }
        }
    }

    /**
     * @param $currentTime
     * @param $cronJobPeriod
     * @return bool
     */
    public function jobShouldRun($currentTime, $cronJobPeriod)
    {
        $currentTime = explode(' ', date('i G j n w', strtotime($currentTime)));
        $cronJobPeriod = explode(' ', $cronJobPeriod);
        foreach ($cronJobPeriod as $i => &$period) {
            $timeVal = intval($currentTime[$i]);
            $period = explode(',', $period);
            foreach ($period as &$periodPart) {
                $periodPart = preg_replace(
                    ['/^\*$/', '/^\d+$/', '/^(\d+)\-(\d+)$/', '/^\*\/(\d+)$/'],
                    ['true', "$timeVal===\\0", "(\\1<=$timeVal  && $timeVal<=\\2)", "$timeVal%\\1===0"],
                    $periodPart
                );
            }
            $period = '(' . implode(' || ', $period) . ')';
        }
        $checkingEquation = implode(' && ', $cronJobPeriod);
        return eval("return $checkingEquation ;");
    }

    /**
     * @param $cmd
     */
    public function execute($cmd)
    {
        exec($cmd);
    }
}