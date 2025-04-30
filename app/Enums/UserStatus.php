<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ACTIVE()
 * @method static static INACTIVE()
 * @method static static BLOCKED()
 * @method static static DELETED()
 */
final class UserStatus extends Enum
{
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
    const BLOCKED = 'BLOCKED';
    const DELETED = 'DELETED';
}
