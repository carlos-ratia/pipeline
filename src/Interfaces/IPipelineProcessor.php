<?php
declare(strict_types=1);


namespace Pipeline\Interfaces;


/**
 * Interface IPipelineProcessor
 * @package BunkerDB\Common\Pipeline\Interfaces
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