<?php
/**
 * Monads: example of creating a Set using Enums
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2017, UK
 * @license GPL V3+ See LICENSE.md
 */

include_once 'vendor/autoload.php';

use MyCLabs\Enum\Enum;
use Monad\Collection;

/**
 * Class SetType
 * @method A()
 * @method B()
 * @method C()
 * @method D()
 */
class SetType extends Enum
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
    const D = 'd';
}

//create a Set by specifying type to be whatever is first in the input array
$setA = new Collection([
    SetType::A(),
    SetType::B(),
    SetType::C()
]);
//create a Set by specifying its expected type and then appending to it
$setB = (new Collection([], '\SetType'))
    ->append(SetType::B())
    ->append(SetType::D());

//Basic set functionality is catered for in the Collection
// NB. implode works, because the contained Enum objects hold scalar values
// which can be stringified

//a,b,c,d
echo implode(',', $setA->vUnion($setB)->toArray()) . PHP_EOL;
//b
echo implode(',', $setA->vIntersect($setB)->toArray()) . PHP_EOL;
//a,c
echo implode(',', $setA->diff($setB)->toArray()) . PHP_EOL;
//d
echo implode(',', $setB->diff($setA)->toArray()) . PHP_EOL;

try {
    //just proves that you cannot add a non SetType to the set
    $setC = $setA->append('foo');
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

//now this will break SET functionality and provides scope for
//extending the Collection class to guard against such eventualities
$setD = $setA->each(function(SetType $v) {
    return SetType::D();
});
//d,d,d - SETS cannot contain multiple same values
echo implode(',', $setD->toArray()) . PHP_EOL;
//what we should have is 'd'
echo implode(',', array_unique($setD->toArray())) . PHP_EOL;