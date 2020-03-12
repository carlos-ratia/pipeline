<?php
declare(strict_types=1);


namespace Cratia\Pipeline\Common;


use Cratia\Pipeline\Interfaces\ICatch;
use Cratia\Pipeline\Interfaces\IPipelineProcessor;
use Cratia\Pipeline\Interfaces\IStage;
use Exception;

/**
 * Class PipelineProcessor
 * @package Pipeline\Common
 */
class PipelineProcessor implements IPipelineProcessor
{
    /**
     * @inheritDoc
     * @throws Exception
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

            // PROCESS ALL STAGES TYPE TYPE_TAP
            // .tapCatch((e) => {}) -> TYPE_TAP
            /** @var ICatch $catch */
            foreach ($catches as $catch) {
                $type = $catch->getType();
                $typeHint = $catch->getTypeHint();
                $closure = $catch->getClosure();
                if ($type === IStage::TYPE_STAGE) {
                    continue;
                }
                if ($typeHint === get_class($e)) {
                    $closure($e);
                }
            }

            // PROCESS ALL STAGES TYPE TYPE_STAGE
            // .tapCatch((e) => {}) -> TYPE_TAP
            /** @var ICatch $catch */
            foreach ($catches as $catch) {
                $type = $catch->getType();
                $typeHint = $catch->getTypeHint();
                $closure = $catch->getClosure();
                if ($type === IStage::TYPE_TAP) {
                    continue;
                }
                if ($typeHint === get_class($e) && $type === IStage::TYPE_STAGE) {
                    return $closure($e);
                }
            }

            // DEFAULT ERROR
            /** @var ICatch $catch */
            foreach ($catches as $catch) {
                $type = $catch->getType();
                $typeHint = $catch->getTypeHint();
                $closure = $catch->getClosure();
                if ($type === IStage::TYPE_TAP) {
                    continue;
                }
                if ($typeHint === Exception::class && $type === IStage::TYPE_STAGE) {
                    return $closure($e);
                }
            }

            throw $e;
        }
        return $result;
    }
}