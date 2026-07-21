<?php

return [
    'Super Admin' => ['*'],



    'Marketing Admin' => [
        'view_dashboard',
        'view_profile',
        'view_campaigns',
        'manage_campaigns',
        'view_referrals',
        'view_users',
        'manage_content',
        'manage_settings_exchange',
    ],

    'Operations Manager' => [
        'view_dashboard',
        'view_profile',
        'view_users',
        'manage_users',
        'view_transactions',
        'manage_transactions',
        'view_beneficiaries',
        'view_reports',
        'view_balances',
    ],

    'Compliance Officer' => [
        'view_dashboard',
        'view_profile',
        'view_users',
        'view_kyc',
        'manage_kyc',
        'view_compliance',
        'manage_compliance',
        'view_transactions',
        'view_reports',
    ],

    'Customer Support Lead' => [
        'view_dashboard',
        'view_profile',
        'view_users',
        'view_personals',
        'view_transactions',
        'view_beneficiaries',
        'view_tickets',
        'manage_tickets',
    ],

    'Finance Admin' => [
        'view_dashboard',
        'view_profile',
        'view_transactions',
        'manage_transactions',
        'view_balances',
        'manage_balances',
        'view_reports',
        'manage_settings',
    ],



    'Product Manager' => [
        'view_dashboard',
        'view_profile',
        'view_users',
        'view_transactions',
        'view_beneficiaries',
        'view_reports',
        'view_product_metrics',
    ],

    'Risk Analyst' => [
        'view_dashboard',
        'view_profile',
        'view_transactions',
        'view_risk',
        'manage_risk',
        'view_fraud',
        'manage_fraud',
        'view_reports',
    ],

    'Security Admin' => [
        'view_dashboard',
        'view_profile',
        'view_admins',
        'view_login_logs',
        'view_system_logs',
        'manage_security',
        'manage_admin_access',
    ],

    'IT Support' => [
        'view_dashboard',
        'view_profile',
        'view_login_logs',
        'view_system_logs',
        'manage_support_tools',
    ],

    'Graphic Designer' => [
        'view_dashboard',
        'view_profile',
        'view_media',
        'manage_media',
        'view_campaigns',
        'manage_content',
    ],
];