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
    const FINISHED_STATUS = [self::STATUS_SUCCESS, self::STATUS_ERROR, self::STATUS_KILLED];

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
