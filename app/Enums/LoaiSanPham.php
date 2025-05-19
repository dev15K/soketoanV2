<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LoaiSanPham extends Enum
{
    const NGUYEN_LIEU_THO = 'NGUYEN_LIEU_THO';
    const NGUYEN_LIEU_PHAN_LOAI = 'NGUYEN_LIEU_PHAN_LOAI';
    const NGUYEN_LIEU_TINH = 'NGUYEN_LIEU_TINH';
    const NGUYEN_LIEU_SAN_XUAT = 'NGUYEN_LIEU_SAN_XUAT';
    const NGUYEN_LIEU_THANH_PHAM = 'NGUYEN_LIEU_THANH_PHAM';
}
