<?php

declare(strict_types=1);

namespace Modules\Subscriber\Enums;

use BenSampo\Enum\Enum;


/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SubscriberStatusEnum extends Enum
{
    const inActive = 1;
    const active = 2;
}
