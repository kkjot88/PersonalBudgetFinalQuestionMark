<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'personal_budget';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const SHOW_ERRORS = true;
    
    const SECRET_KEY = '58C1DE2D452CBC5C2BB183D4C171A';

    const MAILGUN_API_KEY = '991115f876a0c88b4881243b8f573444-8ed21946-02bfdbbb';
    const MAILGUN_DOMAIN = 'sandboxa6191196266440c6ad9889922eada592.mailgun.org';
}
