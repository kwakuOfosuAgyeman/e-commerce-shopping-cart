<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configure security settings including attack detection thresholds,
    | emergency response protocols, and administrator notifications.
    |
    */

    /**
     * Administrator email addresses for security alerts
     */
    'admin_emails' => env('SECURITY_ADMIN_EMAILS')
        ? explode(',', env('SECURITY_ADMIN_EMAILS'))
        : [],

    /**
     * Attack detection thresholds
     */
    'thresholds' => [
        'failed_login_attempts' => env('SECURITY_FAILED_LOGIN_THRESHOLD', 5),
        'failed_login_window' => env('SECURITY_FAILED_LOGIN_WINDOW', 5), // minutes

        'request_rate_limit' => env('SECURITY_REQUEST_RATE_LIMIT', 100),
        'request_rate_window' => env('SECURITY_REQUEST_RATE_WINDOW', 1), // minutes

        'attack_count_for_lockdown' => env('SECURITY_LOCKDOWN_THRESHOLD', 50),
        'attack_count_window' => env('SECURITY_ATTACK_WINDOW', 10), // minutes
    ],

    /**
     * IP blocking durations (in minutes)
     */
    'block_durations' => [
        'brute_force' => env('SECURITY_BLOCK_BRUTE_FORCE', 120),      // 2 hours
        'ddos' => env('SECURITY_BLOCK_DDOS', 60),                      // 1 hour
        'sql_injection' => env('SECURITY_BLOCK_SQL_INJECTION', 1440),  // 24 hours
        'xss' => env('SECURITY_BLOCK_XSS', 1440),                      // 24 hours
        'malicious_upload' => env('SECURITY_BLOCK_MALICIOUS_UPLOAD', 720), // 12 hours
        'default' => env('SECURITY_BLOCK_DEFAULT', 60),                // 1 hour
    ],

    /**
     * Emergency lockdown settings
     */
    'lockdown' => [
        'enabled' => env('SECURITY_LOCKDOWN_ENABLED', true),
        'duration' => env('SECURITY_LOCKDOWN_DURATION', 60), // minutes
        'bypass_token' => env('SECURITY_LOCKDOWN_BYPASS_TOKEN', 'emergency-access-token'),
    ],

    /**
     * IP whitelist - IPs that should never be blocked
     */
    'ip_whitelist' => env('SECURITY_IP_WHITELIST')
        ? explode(',', env('SECURITY_IP_WHITELIST'))
        : [],

    /**
     * Enable/disable specific attack detection features
     */
    'detection_enabled' => [
        'brute_force' => env('SECURITY_DETECT_BRUTE_FORCE', true),
        'ddos' => env('SECURITY_DETECT_DDOS', true),
        'sql_injection' => env('SECURITY_DETECT_SQL_INJECTION', true),
        'xss' => env('SECURITY_DETECT_XSS', true),
        'malicious_upload' => env('SECURITY_DETECT_MALICIOUS_UPLOAD', true),
    ],

    /**
     * Logging configuration
     */
    'logging' => [
        'channel' => env('SECURITY_LOG_CHANNEL', 'security'),
        'level' => env('SECURITY_LOG_LEVEL', 'critical'),
        'store_in_database' => env('SECURITY_STORE_IN_DB', true),
    ],

    /**
     * Notification settings
     */
    'notifications' => [
        'email_enabled' => env('SECURITY_EMAIL_NOTIFICATIONS', true),
        'slack_enabled' => env('SECURITY_SLACK_NOTIFICATIONS', false),
        'slack_webhook' => env('SECURITY_SLACK_WEBHOOK'),
        'notify_on_lockdown' => env('SECURITY_NOTIFY_LOCKDOWN', true),
        'notify_on_critical' => env('SECURITY_NOTIFY_CRITICAL', true),
        'notify_on_high' => env('SECURITY_NOTIFY_HIGH', false),
    ],

    /**
     * Automatic response settings
     */
    'auto_response' => [
        'block_on_brute_force' => env('SECURITY_AUTO_BLOCK_BRUTE_FORCE', true),
        'block_on_sql_injection' => env('SECURITY_AUTO_BLOCK_SQL_INJECTION', true),
        'block_on_xss' => env('SECURITY_AUTO_BLOCK_XSS', true),
        'lockdown_on_threshold' => env('SECURITY_AUTO_LOCKDOWN', true),
    ],

    /**
     * Security dashboard settings
     */
    'dashboard' => [
        'enabled' => env('SECURITY_DASHBOARD_ENABLED', true),
        'route' => env('SECURITY_DASHBOARD_ROUTE', 'admin/security'),
        'middleware' => ['auth', 'role:admin'],
    ],

    /**
     * Threat intelligence
     */
    'threat_intelligence' => [
        'enabled' => env('SECURITY_THREAT_INTEL_ENABLED', false),
        'api_key' => env('SECURITY_THREAT_INTEL_API_KEY'),
        'check_known_malicious_ips' => env('SECURITY_CHECK_MALICIOUS_IPS', false),
    ],

];
