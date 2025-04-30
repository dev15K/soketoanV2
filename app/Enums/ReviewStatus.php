<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static APPROVED()
 * @method static static DELETED()
 * @method static static REJECTED()
 */
final class ReviewStatus extends Enum
{
    const PENDING = 'CHỜ PHÊ DUYỆT';
    const APPROVED = 'ĐƯỢC CHẤP NHẬN';
    const DELETED = 'ĐÃ XOÁ';
    const REJECTED = 'ĐÃ TỪ CHỐI';
}
