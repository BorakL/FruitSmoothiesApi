<?php
final class Configuration{
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = 'root';
    const DATABASE_PASS = '';
    const DATABASE_NAME = 'desserts';

    const SESSION_STORAGE = '\\App\\Core\\Session\\FileSessionStorage';
    const SESSION_STORAGE_DATA = ['./sessions/'];
    const SESSION_LIFETIME = 3600;
	
    const UPLOAD_DIR = 'assets/uploads/';
}