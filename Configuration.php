<?php
/**
 * @author hezag
 * @link http://github.com/hezag
 * @license The MIT License, http://opensource.org/licenses/MIT
 * @version 0.1.0
 */

global $CCONFIG;

$CCONFIG['DATABASE']['HOST'] = 'localhost';
$CCONFIG['DATABASE']['PORT'] = 3306;
$CCONFIG['DATABASE']['USER'] = 'watchr';
$CCONFIG['DATABASE']['PASS'] = '';
$CCONFIG['DATABASE']['NAME'] = 'watchr';

$CCONFIG['SITE']['SHORT_URL'] = 'wtch.cf';
$CCONFIG['SITE']['WWW'] = ''; // WITHOUT the first '/'
$CCONFIG['SITE']['STATIC'] = '/static';

// Debug mode: TRUE or FALSE
define('C_DEBUG', TRUE);
