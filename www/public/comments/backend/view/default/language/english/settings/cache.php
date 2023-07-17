<?php
// Heading
$_['lang_heading']                 = 'Cache';

// Description
$_['lang_description']             = '<p>Enable one of the caching options below to reduce database queries when retrieving comments.</p><p class="extra">Cache is automatically refreshed, for example after a user has posted a comment.</p>';

// Entry
$_['lang_entry_type']              = 'Type:';
$_['lang_entry_time']              = 'Expire:';
$_['lang_entry_host']              = 'Host:';
$_['lang_entry_port']              = 'Port:';

// Selection
$_['lang_select_none']             = 'None';
$_['lang_select_file']             = 'File';
$_['lang_select_memcached']        = 'Memcached';
$_['lang_select_redis']            = 'Redis';

// Hint
$_['lang_hint_type']               = 'Choose a caching method. Redis and Memcached are preferred.';

// Error
$_['lang_error_file']              = 'The folder /commentics/system/cache/database/ is not writable';
$_['lang_error_memcached_class']   = 'Memcached is not installed on your server';
$_['lang_error_memcached_connect'] = 'There was an error connecting to Memcached. Check your host and port settings.';
$_['lang_error_redis_class']       = 'Redis is not installed on your server';
