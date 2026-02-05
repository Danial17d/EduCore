<?php


namespace App\Enums;
enum UserRole: string
{
    case ADMIN = 'admin';
    case STUDENT = 'student';
    case INSTRUCTOR = 'instructor';
}
