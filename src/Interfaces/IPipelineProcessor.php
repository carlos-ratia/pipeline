<?php
declare(strict_types=1);


namespace Pipeline\Interfaces;


/**
 * Interface IPipelineProcessor
 * @package Pipeline\Interfaces
 */
interface IPipelineProcessor
{
    /**
     * @param IStage[] $stages
     * @param ICatch[] $catches
     * @return mixed
     */
    public function __invoke($stages, $catches);
}