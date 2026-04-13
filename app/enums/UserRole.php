<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MITRA = 'mitra';
    case PELAMAR = 'pelamar';
}