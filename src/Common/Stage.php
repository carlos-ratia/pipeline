<?php
declare(strict_types=1);


namespace Cratia\Pipeline\Common;

use Closure;
use Cratia\Pipeline\Interfaces\IStage;

/**
 * Class Stage
 * @package Pipeline\Common
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