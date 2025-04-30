<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static PROCESSING()
 * @method static static CONFIRMED()
 * @method static static COMPLETED()
 * @method static static CANCELED()
 * @method static static DELETED()
 */
final class ConsultantStatus extends Enum
{
    const PENDING = 'CHỜ XÁC NHẬN';
    const PROCESSING = 'ĐANG XỬ LÝ';
    const CONFIRMED = 'ĐÃ XÁC NHẬN';
    const COMPLETED = 'ĐÃ HOÀN THÀNH';
    const CANCELED = 'ĐÃ HỦY';
    const DELETED = 'ĐÃ XÓA';
}
