<?php

namespace AXM\TD\Test;

use AXM\TD\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    public function testIsFinished()
    {
        $this->assertTrue(Job::isFinished('success'));
    }
}
