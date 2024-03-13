<?php

return [

    'email_verification_expiration_seconds' => env('EMAIL_VERIFICATION_EXPIRATION_SECONDS', 86400),
    'password_reset_expiration_seconds' => env('PASSWORD_RESET_EXPIRATION_SECONDS', 86400),
    'frontend_url' => env('FRONTEND_URL', "http://localhost:3000"),
    'frontend_path_password_reset' => env('FRONTEND_PATH_PASSWORD_RESET', "/auth/reset-password"),
    'frontend_path_email_verification' => env('FRONTEND_PATH_EMAIL_VERIFICATION', "/auth/verify-email"),
    'frontend_path_contact_us' => env('FRONTEND_PATH_CONTACT_US', "/contact-us"),

];
