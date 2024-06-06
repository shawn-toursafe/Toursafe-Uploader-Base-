<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */

    'registration' => env('GENERAL_SETTINGS_REGISTRATION'),

    'email_verification' => env('GENERAL_SETTINGS_EMAIL_VERIFICATION'),

    'oauth_login' => env('GENERAL_SETTINGS_OAUTH_LOGIN'),

    'default_user' => env('GENERAL_SETTINGS_DEFAULT_USER_GROUP'),

    'default_country' => env('GENERAL_SETTINGS_DEFAULT_COUNTRY'),

    'support_email' => env('GENERAL_SETTINGS_SUPPORT_EMAIL'),

    'user_notification' => env('GENERAL_SETTINGS_USER_NOTIFICATION'),

    'user_support' => env('GENERAL_SETTINGS_USER_SUPPORT'),

    /*
    |--------------------------------------------------------------------------
    | Archive Settings
    |--------------------------------------------------------------------------
    */

    'default_storage_size' => env('ARCHIVE_SETTINGS_DEFAULT_STORAGE_SIZE'),

    'multipart_chunk_size' => env('ARCHIVE_SETTINGS_MULTIPART_CHUNK_SIZE'),

    'upload_limit_subscriber' => env('ARCHIVE_SETTINGS_UPLOAD_LIMIT_SUBSCRIBER'),

    'upload_limit_user' => env('ARCHIVE_SETTINGS_UPLOAD_LIMIT_USER'),

    'upload_quantity_subscriber' => env('ARCHIVE_SETTINGS_UPLOAD_QUANTITY_SUBSCRIBER'),

    'upload_quantity_user' => env('ARCHIVE_SETTINGS_UPLOAD_QUANTITY_USER'),

    'download_days' => env('ARCHIVE_SETTINGS_DOWNLOAD_DAYS'),

    'storage_type' => env('ARCHIVE_SETTINGS_STORAGE_TYPE'),

];
