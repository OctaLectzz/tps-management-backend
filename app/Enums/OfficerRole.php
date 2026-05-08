<?php

namespace App\Enums;

enum OfficerRole: string
{
    case Coordinator = 'coordinator';
    case Kpps = 'kpps';
    case Witness = 'witness';
    case Observer = 'observer';
}
