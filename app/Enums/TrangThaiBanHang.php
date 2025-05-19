<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static DELETED()
 */
final class TrangThaiBanHang extends Enum
{
    const ACTIVE = 'ĐÃ BÁN';
    const DELETED = 'ĐÃ XOÁ';
}
