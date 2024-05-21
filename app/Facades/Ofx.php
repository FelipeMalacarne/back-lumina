<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static
 *
 * @see \App\Service\Ofx
 */
class Ofx extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ofxParser';
    }
}
