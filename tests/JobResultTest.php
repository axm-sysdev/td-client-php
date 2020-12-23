<?php

namespace AXM\TD\Test;

use AXM\TD\JobResult;
use PHPUnit\Framework\TestCase;

class JobResultTest extends TestCase
{
    public function testInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new JobResult('test');
    }

    public function testToArray()
    {
        $jobResult = new JobResult(JobResult::FORMAT_JSON);
        $contents = <<< EOM
["1","test data1"]
["2","test data2"]
EOM;
        $expected = [
            [1, 'test data1'],
            [2, 'test data2'],
        ];

        $this->assertEquals($expected, $jobResult->toArray($contents));
    }
}
