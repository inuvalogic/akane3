<?php
if (!defined('MAIL_DEBUG')){
    define('MAIL_DEBUG', true);
}
if (!defined('MAIL_LOGS')){
    define('MAIL_LOGS', true);
}
if (!defined('MAIL_FROM')){
    define('MAIL_FROM', 'akane@webhade.com');
}
if (!defined('MAIL_FROM_NAME')){
    define('MAIL_FROM_NAME', 'Akane');
}
if (!defined('MAIL_SMTP_HOSTNAME')){
    define('MAIL_SMTP_HOSTNAME', 'smtp.mailtrap.io');
}
if (!defined('MAIL_SMTP_USERNAME')){
    define('MAIL_SMTP_USERNAME', null);
}
if (!defined('MAIL_SMTP_PASSWORD')){
    define('MAIL_SMTP_PASSWORD', null);
}
if (!defined('MAIL_SMTP_PORT')){
    define('MAIL_SMTP_PORT', 2525);
}
if (!defined('MAILGUN_KEY')){
    define('MAILGUN_KEY', '');
}
if (!defined('MAILGUN_DOMAIN')){
    define('MAILGUN_DOMAIN', '');
}