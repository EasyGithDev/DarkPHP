<?php

include '../bootstrap.php';

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Création des instances';
echo '<br/>-----------------------------------------------------------------<br/>';

$c1 = DbConnection::create()
	->setName('R')
	->setHost('localhost')
	->setUser('root')
	->setPassword('')
	->setDb('backoffice');

$c2 = DbConnection::create()
	->setName('W')
	->setHost('127.0.0.1')
	->setUser('root')
	->setPassword('')
	->setDb('test');

$db = Db::getInstance($c1);
$db = Db::getInstance($c2);

//var_export(Dark\Db::getInstance());
//var_export(Dark\Db::getInstance('R'));
//var_export(Dark\Db::getInstance('W'));
//var_export(Dark\Db::getInstance('Z'));

$db = Db::getInstance();

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Iterateur';
echo '<br/>-----------------------------------------------------------------<br/>';

$it = $db->fetchIterator('SELECT * FROM users');

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Nombre de lignes : ', $it->count();
echo '<br/>-----------------------------------------------------------------<br/>';

foreach ($it as $v) {
    echo $v['email'],  '<br/>';
}

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Une ligne';
echo '<br/>-----------------------------------------------------------------<br/>';


$row = $db->fetchOne('SELECT * FROM users');
print_r($row);

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Tableau complet';
echo '<br/>-----------------------------------------------------------------<br/>';

$rows = $db->fetchAll('SELECT * FROM users');
print_r($rows);
echo '<br>';

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'JSON';
echo '<br/>-----------------------------------------------------------------<br/>';

$json = $db->fetchJSON('SELECT * FROM users');
print_r($json);
echo '<br>';


echo '<br/>-----------------------------------------------------------------<br/>';
echo 'INSERTION';
echo '<br/>-----------------------------------------------------------------<br/>';

$values = array(
    'username' => 'sss',
    'password' => 'd"d"d\'d',
    'email' => 'bisous@bobo.fr'
);


$db->insert('users', $values, true);

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Identifiant de l\'insertion : ', $db->getInsertId();
echo '<br/>-----------------------------------------------------------------<br/>';

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'MISE A JOUR';
echo '<br/>-----------------------------------------------------------------<br/>';

$db->update('users', array('username' => 'supermama'), array(array('id', '=', 41), array('AND'), array('username', '=', 'super')));

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Nombre de lignes mises à jour : ', $db->getAffectedRows();
echo '<br/>-----------------------------------------------------------------<br/>';


echo '<br/>-----------------------------------------------------------------<br/>';
echo 'SUPPRESSION';
echo '<br/>-----------------------------------------------------------------<br/>';

$db->delete('users', array(array('id', '=', 22)));

echo '<br/>-----------------------------------------------------------------<br/>';
echo 'Nombre de lignes supprimées : ', $db->getAffectedRows();
echo '<br/>-----------------------------------------------------------------<br/>';
?>