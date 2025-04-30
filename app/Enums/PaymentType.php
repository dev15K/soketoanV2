<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static IMMEDIATE()
 * @method static static ELECTRONIC_WALLET()
 * @method static static MONEY_TRANSFER()
 * @method static static CARD_CREDIT()
 */
final class PaymentType extends Enum
{
    const IMMEDIATE = "Thanh toán khi nhận hàng";
    const ELECTRONIC_WALLET = "Ví điện tử";
    const MONEY_TRANSFER = "Chuyển tiền trực tiếp";
    const CARD_CREDIT = "Thẻ ghi nợ hoặc thẻ tín dụng";
}
