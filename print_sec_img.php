<?php
/**
 *
 * This file is part of HESK - PHP Help Desk Software.
 *
 * (c) Copyright Klemen Stirn. All rights reserved.
 * https://www.hesk.com
 *
 * For the full copyright and license agreement information visit
 * https://www.hesk.com/eula.php
 *
 */

define('IN_SCRIPT',1);
define('HESK_PATH','./');

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
require(HESK_PATH . 'inc/secimg.inc.php');

hesk_session_start('CUSTOMER');

// We may have more than one instance of the anti-spam image on the page
$page = hesk_GET('p', '');

// Let's allow whitelisted page variables only
if ( ! in_array($page, array('', 'reset'))) {
    $page = '';
}

$_SESSION['secnum' . $page]   = mt_rand(10000,99999);
$_SESSION['checksum' . $page] = sha1($_SESSION['secnum' . $page] . $hesk_settings['secimg_sum']);

/* This will make sure the security image is not cached */
header("expires: -1");
header("cache-control: no-cache, no-store, must-revalidate, max-age=-1");
header("cache-control: post-check=0, pre-check=0", false);
header("pragma: no-store,no-cache");

$sc = new PJ_SecurityImage($hesk_settings['secimg_sum']);
$sc->printImage($_SESSION['secnum' . $page]);

exit();
