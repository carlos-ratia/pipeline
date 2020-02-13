<?php

namespace Test;


use Cratia\Pipeline;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

/**
 * Class PipelineProcessorTest
 * @package Test
 */
class PipelineProcessorTest extends PHPUnit_TestCase
{
    public function testInvoke()
    {
        $pipe = Pipeline::try(
            function () {
                return [1, 2, 3];
            })
            ->then(function ($data) {
                return array_sum($data);
            })
            ->catch(function (Exception $e) {
                return -1;
            });

        $processor = new Pipeline\Common\PipelineProcessor();
        $result = $processor->__invoke($pipe->getStages(), $pipe->getCatches());

        $this->assertEquals(6, $result);
    }
}