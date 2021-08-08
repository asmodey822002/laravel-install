<?php

return [
    [
        'desc' => 'Application info',
        'content' => [
            [
                'variable' => 'APP_NAME',
                'description' => 'Application name',
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'APP_ENV',
                'description' => 'Application environment type',
                'choise' => [
                    'local',
                    'production',
                    'staging'
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'APP_DEBUG',
                'description' => 'Enable debug mode',
                'choise' => [
                    'true',
                    'false'
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'APP_URL',
                'description' => 'Application url',
                'type' => 'core',
                'visibility' => true,
            ]
        ]
    ],
    [
        'desc' => 'Database settings',
        'content' => [
            [
                'variable' => 'DB_CONNECTION',
                'description' => 'Database connection',
                'choise' => [
                    'mysql',
                    'sqlite'
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'DB_HOST',
                'description' => 'Database host',
                'default' => '127.0.0.1',
                'type' => 'mysql',
                'visibility' => true,
            ],
            [
                'variable' => 'DB_PORT',
                'description' => 'Database port',
                'default' => '3306',
                'type' => 'mysql',
                'visibility' => true,
            ],
            [
                'variable' => 'DB_DATABASE',
                'description' => 'Database name',
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'DB_USERNAME',
                'description' => 'Database username',
                'type' => 'mysql',
                'visibility' => true,
            ],
            [
                'variable' => 'DB_PASSWORD',
                'description' => 'Database password',
                'type' => 'mysql',
                'visibility' => true,
            ],
            [
                'variable' => 'DB_FOREIGN_KEYS',
                'description' => 'Database foregin keys',
                'default' => 'true',
                'type' => 'sqlite',
                'visibility' => false,
            ],
        ]
    ],
    [
        'desc' => 'Drivers settings',
        'content' => [
            [
                'variable' => 'BROADCAST_DRIVER',
                'description' => 'Broadcast driver',
                'choise' => [
                    'log',
                    'redis',
                    'null'
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'CACHE_DRIVER',
                'description' => 'Cache driver',
                'choise' => [
                    'file',
                    'apc',
                    'array',
                    'database',
                    'redis',
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'QUEUE_CONNECTION',
                'description' => 'Queue connection',
                'choise' => [
                    'sync',
                    'database',
                    'beanstalkd',
                    'redis'
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'SESSION_DRIVER',
                'description' => 'Session driver',
                'choise' => [
                    'file',
                    'cookie',
                    'database',
                    'apc',
                    'redis',
                    'array'
                ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'SESSION_LIFETIME',
                'description' => 'Session lifetime in minutes',
                'default' => '120',
                'type' => 'core',
                'visibility' => true,
            ],
        ]
    ],
    [
        'desc' => 'Redis settings',
        'content' => [
            [
                'variable' => 'REDIS_HOST',
                'description' => 'Broadcast driver',
                'default' => '127.0.0.1',
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'REDIS_PASSWORD',
                'description' => 'Redis password',
                'default' => 'null',
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'REDIS_PORT',
                'description' => 'Redis port',
                'default' => '6379',
                'type' => 'core',
                'visibility' => true,
            ]
        ]
    ],
    [
        'desc' => 'Selecting the type of mailer',
        'content' => [
            [
                'variable' => 'TYPE_OF_MAILER',
                'description' => 'Mail mailer',
                'choise' => [
                        'smtp',
                        'mailgun',
                    ],
                'choise_default_index' => 0,
                'type' => 'core',
                'visibility' => true,
            ],
        ],
    ],
    [
        'desc' => 'Drivers mailer settings',
        'content' => [
            [
                'variable' => 'MAIL_DRIVER',
                'description' => 'Mail mailer',
                'default' => 'mailgun',
                'type' => 'mailgun',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_MAILER',
                'description' => 'Mail mailer',
                'default' => 'smtp',
                'type' => 'smtp',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_HOST',
                'description' => 'Mail host',
                'default' => 'smtp.mailtrap.io',
                'type' => 'smtp',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_PORT',
                'description' => 'Mail port',
                'default' => '2525',
                'type' => 'smtp',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_USERNAME',
                'description' => 'Mail username',
                'default' => 'null',
                'type' => 'smtp',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_PASSWORD',
                'description' => 'Mail password',
                'default' => 'null',
                'type' => 'smtp',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_ENCRYPTION',
                'description' => 'Mail encryption',
                'default' => 'null',
                'type' => 'smtp',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_FROM_ADDRESS',
                'description' => 'Mail from address',
                'default' => 'null',
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'MAIL_FROM_NAME',
                'description' => 'Mail from name',
                'default' => '"${APP_NAME}"',
                'type' => 'core',
                'visibility' => true,
            ],
            [
                'variable' => 'MAILGUN_DOMAIN',
                'description' => 'Domain from Mailgun',
                'default' => 'null',
                'type' => 'mailgun',
                'visibility' => true,
            ],
            [
                'variable' => 'MAILGUN_SECRET',
                'description' => 'Password from Mailgun',
                'default' => 'null',
                'type' => 'mailgun',
                'visibility' => true,
            ],
            [
                'variable' => 'MAILGUN_ENDPOINT',
                'description' => 'Mailgun endpoint',
                'default' => 'api.mailgun.net',
                'type' => 'mailgun',
                'visibility' => true,
            ]
        ]
    ]
];
