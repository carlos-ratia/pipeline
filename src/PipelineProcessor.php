<?php
declare(strict_types=1);


namespace BunkerDB\Common\Pipeline;


use BunkerDb\Common\Pipeline\Interfaces\ICatch;
use BunkerDB\Common\Pipeline\Interfaces\IPipelineProcessor;
use BunkerDb\Common\Pipeline\Interfaces\IStage;
use Exception;

/**
 * Class BasePipelineProcessor
 * @package App\Application\Models\ORM\Common
 */
class PipelineProcessor implements IPipelineProcessor
{
    /**
     * @inheritDoc
     */
    public function __invoke($stages, $catches)
    {
        $result = null;
        try {
            /** @var IStage $stage */
            foreach ($stages as $stage) {
                $type = $stage->getType();
                $closure = $stage->getClosure();
                if ($type === IStage::TYPE_TAP) {
                    $closure($result);
                } elseif ($type === IStage::TYPE_STAGE) {
                    $result = $closure($result);
                }
            }
        } catch (Exception $e) {
            /** @var ICatch $catch */
            foreach ($catches as $catch) {
                $type = $catch->getType();
                $typeHint = $catch->getTypeHint();
                $closure = $catch->getClosure();
                if ($type !== IStage::TYPE_TAP) {
                    continue;
                }
                if ($typeHint === get_class($e)) {
                    $closure($e);
                }
            }
            /** @var ICatch $catch */
            foreach ($catches as $catch) {
                $type = $catch->getType();
                $typeHint = $catch->getTypeHint();
                $closure = $catch->getClosure();
                if ($type !== IStage::TYPE_STAGE) {
                    continue;
                }
                if ($typeHint === get_class($e) && $type === IStage::TYPE_STAGE) {
                    return $closure($e);
                }
            }
        }
        return $result;
    }
}