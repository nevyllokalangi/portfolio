<?php
// Security configurations
define('CSRF_TOKEN_SECRET', bin2hex(random_bytes(32)));
define('CSRF_TOKEN_EXPIRY', 3600); // 1 hour
define('LOGIN_ATTEMPTS_LIMIT', 5);
define('LOGIN_ATTEMPTS_TIMEFRAME', 15 * 60); // 15 minutes
define('LOGIN_LOCKOUT_TIME', 30 * 60); // 30 minutes
define('PASSWORD_RESET_EXPIRE', 3600); // 1 hour
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('REMEMBER_ME_EXPIRE', 30 * 24 * 3600); // 30 days

// Session configuration
session_name('NEVYLLO_SESSID');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '0');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', SESSION_TIMEOUT);
ini_set('session.cookie_lifetime', 0);
ini_set('session.hash_function', 'sha256');
ini_set('session.use_trans_sid', 0);