<?php
declare(strict_types=1);


namespace BunkerDB\Common;


use BunkerDb\Common\Pipeline\Interfaces\ICatch;
use BunkerDB\Common\Pipeline\Interfaces\IPipeline;
use BunkerDB\Common\Pipeline\Interfaces\IPipelineProcessor;
use BunkerDb\Common\Pipeline\Interfaces\IStage;
use BunkerDB\Common\Pipeline\PipelineProcessor;
use BunkerDB\Common\Pipeline\Stage;
use BunkerDB\Common\Pipeline\StageCatch;
use Closure;

/**
 * Class Pipeline
 * @package BunkerDB\Common
 */
class Pipeline implements IPipeline
{
    /**
     * @var IPipelineProcessor
     */
    private $processor;

    /**
     * @var IStage[]
     */
    private $stages;

    /**
     * @var ICatch[]
     */
    private $catches;

    /**
     * Pipeline constructor.
     * @param IPipelineProcessor $processor
     */
    private function __construct(IPipelineProcessor $processor)
    {
        $this->processor = $processor;
        $this->stages = [];
        $this->catches = [];
    }

    /**
     * @return IPipelineProcessor
     */
    public function getProcessor(): IPipelineProcessor
    {
        return $this->processor;
    }

    /**
     * @param IPipelineProcessor $processor
     * @return Pipeline
     */
    public function setProcessor(IPipelineProcessor $processor): IPipeline
    {
        $this->processor = $processor;
        return $this;
    }

    /**
     * @return IStage[]
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @return ICatch[]
     */
    public function getCatches()
    {
        return $this->catches;
    }

    /**
     * @param Closure $stage
     * @param IPipelineProcessor $processor
     * @return Pipeline
     */
    public static function try(Closure $stage, IPipelineProcessor $processor = null)
    {
        if (is_null($processor)) {
            $processor = new PipelineProcessor();
        }
        return (new Pipeline($processor))->then($stage);
    }

    /**
     * @param Closure $stage
     * @return Pipeline
     */
    public function then(Closure $stage): IPipeline
    {
        $this->stages[] = new Stage(Stage::TYPE_STAGE, $stage);
        return $this;
    }

    /**
     * @param Closure $stage
     * @return Pipeline
     */
    public function tap(Closure $stage): IPipeline
    {
        $this->stages[] = new Stage(Stage::TYPE_TAP, $stage);
        return $this;
    }

    /**
     * @param IPipeline $pipeline
     * @return IPipeline
     */
    public function join(IPipeline $pipeline): IPipeline
    {
        foreach ($pipeline->getStages() as $stage) {
            $this->stages[] = $stage;
        }
        foreach ($pipeline->getCatches() as $catch) {
            $this->catches[] = $catch;
        }
        return $this;
    }

    /**
     * @param Closure $catch
     * @return IPipeline
     */
    public function catch(Closure $catch): IPipeline
    {
        $this->catches[] = new StageCatch(IStage::TYPE_STAGE, $catch);
        return $this;
    }

    /**
     * @param Closure $catch
     * @return IPipeline
     */
    public function tapCatch(Closure $catch): IPipeline
    {
        $this->catches[] = new StageCatch(IStage::TYPE_TAP, $catch);
        return $this;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->process();
    }

    /**
     * @return mixed
     */
    public function process()
    {
        return $this->processor->__invoke($this->stages, $this->catches);
    }
}