<?php

namespace App\Http\Enums;

enum UserTypeEnums: int
{
    case ADMIN = 1;
    case MODERATOR = 2;
    case USER = 3;
    case NEWBIE = 4;
}
