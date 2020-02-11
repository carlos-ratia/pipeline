<?php
declare(strict_types=1);


namespace Cratia\Pipeline\Interfaces;


use Closure;

/**
 * Interface IStage
 * @package Pipeline\Interfaces
 */
interface IStage
{
    const TYPE_STAGE = 'TYPE_STAGE';
    const TYPE_TAP = 'TYPE_TAP';

    /**
     * @return Closure
     */
    public function getClosure(): Closure;

    /**
     * @return string
     */
    public function getType(): string;
}