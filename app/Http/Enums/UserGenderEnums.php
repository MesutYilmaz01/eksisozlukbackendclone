<?php

namespace App\Http\Enums;

enum UserGenderEnums: int
{
    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;
    case SKIP = 4;
}
