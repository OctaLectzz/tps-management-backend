<?php

namespace App\Enums;

enum ConfirmationStatus: string
{
    case Confirmed = 'confirmed';
    case Pending = 'pending';
    case Absent = 'absent';
}
