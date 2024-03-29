<?php

/*
 * The server you want to proxy to
 * Provide without any ports and protocol
 */ 
$config['server'] = 'localhost';

/*
 * Forwarding ports for http and https
 */
$config['http_port']  = 8000;
$config['https_port'] = 4433;

/*
 * Timeout in seconds
 */
$config['timeout'] = 5;