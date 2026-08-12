<?php

namespace App\Enums;

enum RepairItemStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Rejected = 'rejected';
    case Timeout = 'timeout';
}