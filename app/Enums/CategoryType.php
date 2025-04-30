<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PRODUCTS()
 * @method static static NEWS()
 * @method static static OTHER()
 */
final class CategoryType extends Enum
{
    const PRODUCTS = 'PRODUCTS';
    const NEWS = 'NEWS';
    const OTHER = 'OTHER';
}
