<?php

define('BASE_URL', 'http://localhost/jss_letters/');
define('PUBLIC_URL', BASE_URL . 'public/');
define('XML_SRC_URL', BASE_URL . 'md-src/xml/');
define('LETTER_URL', PUBLIC_URL . 'Letters/');
define('DOWNLOAD_URL', PUBLIC_URL . 'Downloads/');
define('STOCK_IMAGE_URL', PUBLIC_URL . 'images/stock/');
define('RESOURCES_URL', PUBLIC_URL . 'Resources/');

// Physical location of resources
define('PHY_BASE_URL', '/var/www/html/jss_letters/');
define('PHY_PUBLIC_URL', PHY_BASE_URL . 'public/');
define('PHY_XML_SRC_URL', PHY_BASE_URL . 'md-src/xml/');
define('PHY_LETTER_URL', PHY_PUBLIC_URL . 'Letters/');
define('PHY_TXT_URL', PHY_PUBLIC_URL . 'Text/');
define('PHY_DOWNLOAD_URL', PHY_PUBLIC_URL . 'Downloads/');
define('PHY_FLAT_URL', PHY_BASE_URL . 'application/views/flat/');
define('PHY_STOCK_IMAGE_URL', PHY_PUBLIC_URL . 'images/stock/');
define('PHY_RESOURCES_URL', PHY_PUBLIC_URL . 'Resources/');

// Git config
define('GIT_USER_NAME', 'suresh07');
define('GIT_PASSWORD', 'suresh123');
define('GIT_REPO', 'github.com/shivasdst/jss_letters.git');
define('GIT_REMOTE', 'https://' . GIT_USER_NAME . ':' . GIT_PASSWORD . '@' . GIT_REPO);
define('GIT_EMAIL', 'shiva@srirangadigital.com');

define('DB_PREFIX', 'jss');
define('DB_HOST', 'localhost');

// photo will become iitmPHOTO inside
define('DB_NAME', 'letters');

define('jssLETTERS_USER', 'root');
define('jssLETTERS_PASSWORD', 'mysql');

?>
