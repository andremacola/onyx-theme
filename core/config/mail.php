<?php
/**
 * Edit this file to configure SMTP mail service
 *
 * @package Onyx Theme
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/phpmailer_init
 */

return [
	'from'   => 'email@domain.tld',
	'name'   => 'Client Name',
	'host'   => 'smtp.gmail.com',
	'port'   => 465,
	'secure' => 'ssl',
	'auth'   => true,
	'user'   => 'username',
	'pass'   => 'password',
];
