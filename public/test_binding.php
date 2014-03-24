<?php

require '../bootstrap.php';

Dark\Core\Db\DbPool::add(
	\Dark\Core\Db\DbConnector::create(
		(array) \Dark\Core\Config::create()->db
	)
);

$db = Dark\Core\Db\DbPool::get();
$db->setDebug(true);

$sql = 'Select * from users where username = "?"';
$bind = array('florent');
//$db->query($sql, $bind);


$injection = '" OR 1 #';

$sql = "Select * from users where username = '" . $db->quote( $injection ) . "'";
//$it = 
	echo $db->fetchIterator($sql);
die;
foreach ($it as $v)
    print_r ($v);