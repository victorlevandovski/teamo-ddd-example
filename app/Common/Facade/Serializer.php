<?php
declare(strict_types=1);

namespace Teamo\Common\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static serialize($object, string $format)
 * @method static deserialize(string $data, string $type, string $format)
 */
class Serializer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'serializer';
    }
}
