<?php
    /**
     * Your Twitter App Info
     */
    
    // Consumer Key
    define('CONSUMER_KEY', '');
    define('CONSUMER_SECRET', '');

    // User Access Token
    define('ACCESS_TOKEN', '');
    define('ACCESS_SECRET', '');
	
	// Cache Settings
	define('CACHE_ENABLED', false);
	define('CACHE_LIFETIME', 3600); // in seconds
	define('HASH_SALT', md5(dirname(__FILE__)));