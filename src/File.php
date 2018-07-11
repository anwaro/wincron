<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Micał
 * Date: 10.07.2018
 * Time: 12:35
 */

namespace anwaro\wincron;

class File
{
    const PERIOD_REGEXP = '/([\d*\/,-]+ ){5}/';

    /** @var string $dir */
    private $dir;

    /** @var string $file */
    private $file;

    public function __construct($dir, $file)
    {
        $this->dir = rtrim($dir, '\\/') . '/';
        $this->file = $file;
    }

    /**
     * @return array
     */
    public function getJobs()
    {
        $content = $this->read();
        return $this->parseContent($content);
    }

    /**
     * @return bool|string
     */
    public function read()
    {
        return file_get_contents($this->dir . $this->file);
    }

    /**
     * @param $content
     * @return array
     */
    public function parseContent($content)
    {
        $jobs = [];
        foreach (explode("\n", $content) as $line) {
            if (!$this->isComment($line) && ($job = $this->lineToJob($line))) {
                $jobs[] = $job;
            }
        }
        return $jobs;
    }

    /**
     * @param string $line
     * @return bool
     */
    public function isComment($line)
    {
        $line = trim($line);
        return substr($line, 0, 1) === "#";
    }

    /**
     * @param $line
     * @return array|bool
     */
    public function lineToJob($line)
    {
        $period = $this->extractPeriod($line);
        $cmd = $this->extractCommand($period, $line);
        if ($period && $cmd) {
            return [
                'period' => $period,
                'cmd' => $cmd
            ];
        }
        return false;
    }

    /**
     * @param $string
     * @return bool|string
     */
    public function extractPeriod($string)
    {
        preg_match(File::PERIOD_REGEXP, $string, $matches);
        if (count($matches)) {
            return trim($matches[0]);
        }
        return false;
    }

    /**
     * @param $period
     * @param $string
     * @return bool|string
     */
    public function extractCommand($period, $string)
    {
        if ($period && strpos($string, $period) !== false) {
            return trim(str_replace($period, '', $string));
        }
        return false;
    }
}