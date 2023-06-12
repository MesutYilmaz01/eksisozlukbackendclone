<?php

namespace App\Http\Enums;

enum MessageSendTypes: int
{
    case ONLY_ADMINS = 1;
    case ADMINS_AND_MODERATORS = 2;
    case ADMINS_AND_MODERATORS_AND_USERS = 3;
    case EVERYONE = 4;
}
