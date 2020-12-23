<?php

namespace AXM\TD;

class Job
{
    const STATUS_QUEUED = "queued";
    const STATUS_BOOTING = "booting";
    const STATUS_RUNNING = "running";
    const STATUS_SUCCESS = "success";
    const STATUS_ERROR = "error";
    const STATUS_KILLED = "killed";
    const FINISHED_STATUS = [
        self::STATUS_SUCCESS,
        self::STATUS_ERROR,
        self::STATUS_KILLED
    ];

    const PRIORITY_VERY_LOW = -2;
    const PRIORITY_LOW = -1;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_HIGH = 1;
    const PRIORITY_VERY_HIGH = 2;

    /**
     * job finished check
     *
     * @param string $status
     * @return bool
     */
    public static function isFinished(string $status): bool
    {
        return in_array($status, static::FINISHED_STATUS, true);
    }
}
