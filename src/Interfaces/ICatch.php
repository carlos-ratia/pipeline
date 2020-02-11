<?php
declare(strict_types=1);


namespace Pipeline\Interfaces;


use Closure;

/**
 * Interface ICatch
 * @package BunkerDb\Common\Pipeline\Interfaces
 */
interface ICatch
{
    /**
     * @return Closure
     */
    public function getClosure(): Closure;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTypeHint(): string;
}