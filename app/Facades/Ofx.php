<?php

declare(strict_types=1);

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self load(string $ofxContent)
 * @method static array transactions()
 * @method static array account()
 * @method static array balance()
 * @method static array statement()
 *
 * @see \App\Services\OfxParser
 */
class Ofx extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ofxParser';
    }
}
