<?php

namespace Vnpay\SDK\Enums;

use BenSampo\Enum\Enum;

class PaymentStatus extends Enum
{
    const Created = "created";
    const Captured = "captured";
}
