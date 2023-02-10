<?php

namespace App\Infrastructure\Environment;

enum Environment: string
{
    case DEV = 'dev';
    case PRODUCTION = 'production';
    case TEST = 'test';
}
