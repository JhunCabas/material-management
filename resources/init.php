<?php
include dirname(__FILE__) . '/config.php';

/**
 * Template Initialization
 */
$tmpl = new fTemplating(DOC_ROOT . '/resources/templates/');
$tmpl->set('title',$config["title"]);
$tmpl->set('version',$config['version']);
$tmpl->set('header', 'header.php');
$tmpl->set('menu', 'menu.php');
$tmpl->set('menuInventory','inventory-menu.php');
$tmpl->set('menuAdmin','admin-menu.php');
$tmpl->set('menuDocument','document-menu.php');
$tmpl->set('menuReport','report-menu.php');
$tmpl->set('footer', 'footer.php');
$tmpl->add('js','./resources/library/jquery/js/jquery-1.3.2.min.js');
$tmpl->add('js','./resources/library/jquery/js/jquery-ui-1.7.2.custom.min.js');
$tmpl->add('js','./resources/templates/main.js');
$tmpl->add('js','./resources/library/datejs/date.js');
$tmpl->add('css',array('path' => './resources/library/blueprint/screen.css','media' => 'screen, projection'));
$tmpl->add('css',array('path' => './resources/library/blueprint/print.css','media' => 'print'));
$tmpl->add('css','./resources/library/jquery/css/cupertino/jquery-ui-1.7.2.custom.css');
$tmpl->add('css','./css/style.css');

// Connecting to a MySQL database on the server
$db  = new fDatabase('mysql', $config['db']['dbName'], $config['db']['dbUsername'], $config['db']['password'], $config['db']['dbHost']);
fORMDatabase::attach($db);

fSession::open();