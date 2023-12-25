<?php

declare(strict_types=1);

namespace Modules\Blog\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BlogStatusEnum extends Enum
{
    const draft = 1;
    const published = 2;

}
