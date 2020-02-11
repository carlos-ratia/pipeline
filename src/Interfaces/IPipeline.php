<?php
declare(strict_types=1);


namespace Cratia\Pipeline\Interfaces;


use Closure;

/**
 * Interface IPipeline
 * @package Pipeline\Interfaces
 */
interface IPipeline
{
    /**
     * @return IPipelineProcessor
     */
    public function getProcessor(): IPipelineProcessor;

    /**
     * @param IPipelineProcessor $processor
     * @return IPipeline
     */
    public function setProcessor(IPipelineProcessor $processor): IPipeline;

    /**
     * @return IStage[]
     */
    public function getStages();

    /**
     * @return ICatch[]
     */
    public function getCatches();

    /**
     * @param Closure $stage
     * @param IPipelineProcessor $processor
     * @return IPipeline
     */
    public static function try(Closure $stage, IPipelineProcessor $processor = null);

    /**
     * @param Closure $stage
     * @return IPipeline
     */
    public function then(Closure $stage): IPipeline;

    /**
     * @param IPipeline $pipeline
     * @return IPipeline
     */
    public function join(IPipeline $pipeline): IPipeline;

    /**
     * @param Closure $catch
     * @return IPipeline
     */
    public function catch(Closure $catch): IPipeline;

    /**
     * @param Closure $stage
     * @return IPipeline
     */
    public function tap(Closure $stage): IPipeline;

    /**
     * @param Closure $catch
     * @return IPipeline
     */
    public function tapCatch(Closure $catch): IPipeline;

    /**
     * @return mixed
     */
    public function __invoke();

    /**
     * @return mixed
     */
    public function process();
}