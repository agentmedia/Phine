<?php
if (!defined('PHINE_PATH'))
    define('PHINE_PATH', __DIR__ . '/../../../../');

require_once(PHINE_PATH . 'Framework/System/PackageBuilder.php');
Phine\Framework\System\PackageBuilder::RequireFiles(__FILE__);
