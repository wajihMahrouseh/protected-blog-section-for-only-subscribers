<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRoleEnum extends Enum
{
    const admin = 'admin';
    const subscriber = 'subscriber';
}
