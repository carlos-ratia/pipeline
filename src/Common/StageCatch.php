<?php
declare(strict_types=1);


namespace Cratia\Pipeline\Common;

use Closure;
use Cratia\Pipeline\Interfaces\ICatch;
use Cratia\Pipeline\Interfaces\IStage;
use Exception;
use ReflectionFunction;
use ReflectionParameter;

/**
 * Class StageCatch
 * @package Pipeline\Common
 */
class StageCatch extends Stage implements IStage, ICatch
{
    /**
     * @var string
     */
    private $typeHint;

    /**
     * PipelineCatch constructor.
     * @param string $type
     * @param Closure $closure
     */
    public function __construct(string $type, Closure $closure)
    {
        $typeHint = Exception::class;
        try {
            /** @var ReflectionFunction $reflection */
            $reflection = new ReflectionFunction($closure);
            /** @var ReflectionParameter[] $params */
            $params = $reflection->getParameters();
            if (isset($params[0])) {
                $typeHint = $params[0]->getType()->getName();
            }
        } catch (Exception $e) {
            $typeHint = Exception::class;
        }
        $this->typeHint = $typeHint;
        parent::__construct($type, $closure);
    }

    /**
     * @return string
     */
    public function getTypeHint(): string
    {
        return $this->typeHint;
    }
}