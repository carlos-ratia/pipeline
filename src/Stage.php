<?php
declare(strict_types=1);


namespace BunkerDB\Common\Pipeline;

use BunkerDb\Common\Pipeline\Interfaces\IStage;
use Closure;

/**
 * Class Stage
 * @package BunkerDB\Common\Pipeline
 */
class Stage implements IStage
{
    /**
     * @var Closure
     */
    private $closure;
    /**
     * @var string
     */
    private $type;

    /**
     * PipeLineStage constructor.
     * @param string $type
     * @param Closure $closure
     */
    public function __construct(string $type, Closure $closure)
    {
        $this->type = $type;
        $this->closure = $closure;
    }

    /**
     * @return Closure
     */
    public function getClosure(): Closure
    {
        return $this->closure;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}