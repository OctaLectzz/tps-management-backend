<?php

namespace App\Enums;

enum PollingStationStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Review = 'review';
}
