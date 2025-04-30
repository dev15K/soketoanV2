<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductType extends Enum
{
    const OptionOne = 'Đặc sản Tây Bắc';
    const OptionTwo = 'Hàng nội địa Trung có sẵn';
    const OptionThree = 'Hàng nội địa Trung order';
}
