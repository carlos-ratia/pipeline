<?php
declare(strict_types=1);


namespace Test;


use Exception;
use LogicException;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Pipeline\Pipeline;


/**
 * Class PipelineTest
 * @package Test
 */
class PipelineTest extends PHPUnit_TestCase
{
    private $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    public function testTry1()
    {
        $result = Pipeline::try(
            function () {
                return [1, 2, 3];
            })
            ->then(function ($data) {
                return array_sum($data);
            })
            ->catch(function (Exception $e) {
                return -1;
            })();
        $this->assertEquals(6, $result);
    }

    public function testTry2()
    {
        $result = Pipeline::try(
            function () {
                return $this->data;
            })
            ->then(function ($data) {
                return array_filter($data, function ($datum) {
                    if ($datum % 2 === 0) {
                        return true;
                    } else {
                        return false;
                    }
                });
            })
            ->then(function ($data) {
                return array_sum($data);
            })
            ->catch(function (Exception $e) {
                return -1;
            })();
        $this->assertEquals(30, $result);
    }

    public function testTry3()
    {
        $result = Pipeline::try(
            function () {
                return $this->data;
            })
            ->then(function ($data) {
                return array_filter($data, function ($datum) {
                    if ($datum % 2 === 0) {
                        throw new LogicException("TEST1");
                    } else {
                        throw new Exception("TEST2");
                    }
                });
            })
            ->then(function ($data) {
                return array_sum($data);
            })
            ->catch(function (LogicException $e) {
                return $e->getMessage();
            })
            ->catch(function (Exception $e) {
                return $e->getMessage();
            })();
        $this->assertEquals("TEST2", $result);
        $this->assertNotEquals("TEST1", $result);
    }

    public function testTry4()
    {
        $result = Pipeline::try(
            function () {
                return $this->data;
            })
            ->then(function ($data1) {
                return array_filter($data1, function ($datum) {
                    if ($datum % 2 === 0) {
                        return true;
                    }
                });
            })
            ->then(function ($data2) {
                $data2[] = 100;
                return $data2;
            })
            ->then(function ($data3) {
                $data3[] = 1000;
                return $data3;
            })
            ->tap(function ($data4) {
                return $data4[] = -1;
            })
            ->then(function ($data5) {
                return array_values($data5);
            })
            ->tap(function ($data6) {
                return $data6[] = -2;
            })
            ->then(function ($data7) {
                return array_values($data7);
            })();
        $this->assertEquals([2, 4, 6, 8, 10, 100, 1000], $result);
    }

    public function testTry5()
    {
        $tap1 = false;
        $tap2 = false;

        $result = Pipeline::try(
            function () {
                return $this->data;
            })
            ->then(function ($data1) {
                return array_filter($data1, function ($datum) {
                    if ($datum % 2 === 0) {
                        return true;
                    }
                });
            })
            ->then(function ($data2) {
                $data2[] = 100;
                return $data2;
            })
            ->then(function ($data3) {
                $data3[] = 1000;
                return $data3;
            })
            ->tap(function ($data4) use (&$tap1) {
                $tap1 = true;
                return $data4[] = -1;
            })
            ->then(function ($data5) {
                return array_values($data5);
            })
            ->tap(function ($data6) use (&$tap2) {
                $tap2 = true;
                return $data6[] = -2;
            })
            ->then(function ($data7) {
                return array_values($data7);
            })();
        $this->assertEquals([2, 4, 6, 8, 10, 100, 1000], $result);
        $this->assertEquals(true, $tap1);
        $this->assertEquals(true, $tap2);
    }

    public function testTry6()
    {
        $tap1 = false;
        $tap2 = false;

        $result = Pipeline::try(
            function () {
                return $this->data;
            })
            ->then(function ($data) {
                throw new LogicException("TEST TRY 6");
            })
            ->then(function ($data) {
                return array_sum($data);
            })
            ->catch(function (LogicException $e) {
                return -2;
            })
            ->catch(function (Exception $e) {
                return -1;
            })
            ->tapCatch(function (LogicException $e) use (&$tap1) {
                $tap1 = true;
                return -10;
            })
            ->tapCatch(function (Exception $e) use (&$tap2) {
                $tap2 = true;
                return -10;
            })
        ();
        $this->assertEquals(-2, $result);
        $this->assertEquals(true, $tap1);
        $this->assertEquals(false, $tap2);
    }
}