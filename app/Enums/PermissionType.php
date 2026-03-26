<?php


namespace App\Enums;
enum PermissionType: string
{

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    case UserList = 'user:list';
    case UserCreate = 'user:create';
    case UserUpdate = 'user:update';
    case UserDelete = 'user:delete';
    case UserView = 'user:view';
    /*
    |--------------------------------------------------------------------------
    | User Profile Management
    |--------------------------------------------------------------------------
    */
    case ProfileView = 'profile:view';
    case ProfileUpdate = 'profile:update';
    case ProfileDelete = 'profile:delete';


    /*
    |--------------------------------------------------------------------------
    | Course Management
    |--------------------------------------------------------------------------
    */
    case CourseList = 'course:list';
    case CourseCreate = 'course:create';
    case CourseUpdate = 'course:update';
    case CourseDelete = 'course:delete';
    case CourseView = 'course:view';

    /*
    |--------------------------------------------------------------------------
    | Enrollment Management
    |--------------------------------------------------------------------------
    */
    case EnrollmentList = 'enrollment:list';
    case EnrollmentCreate = 'enrollment:create';
    case EnrollmentUpdate = 'enrollment:update';
    case EnrollmentDelete = 'enrollment:delete';
    case EnrollmentView = 'enrollment:view';
    /*
    |--------------------------------------------------------------------------
    | Course Instructor Management
    |--------------------------------------------------------------------------
    */
    case CourseInstructorList = 'course:instructor:list';
    case CourseInstructorCreate = 'course:instructor:create';
    case CourseInstructorUpdate = 'course:instructor:update';
    case CourseInstructorDelete = 'course:instructor:delete';
    case CourseInstructorView = 'course:instructor:view';

    /*
    |--------------------------------------------------------------------------
    | Dashboard Management
    |--------------------------------------------------------------------------
    */
    case DashboardView = 'dashboard:view';
    /*
    |--------------------------------------------------------------------------
    | Experience Management
    |--------------------------------------------------------------------------
    */
    case ExperienceCreate = 'experience:create';
    case ExperienceDelete = 'experience:delete';
    case ExperienceUpdate = 'experience:update';

    /*
   |--------------------------------------------------------------------------
   | Experience Management
   |--------------------------------------------------------------------------
   */
    case LessonList = 'lesson:list';
    case LessonCreate = 'lesson:create';
    case LessonUpdate = 'lesson:update';
    case LessonDelete = 'lesson:delete';
    case LessonView = 'lesson:view';


}
