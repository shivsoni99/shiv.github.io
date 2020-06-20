<?php
$host = "127.0.0.1";
$port = 3306;
$username = "username";
$password = "password";
$database = "day-week-month";

$db = new PDO("mysql:host=$host;port=$port",
    $username,
    $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// other init
date_default_timezone_set("UTC");
session_start();

$db->exec("CREATE DATABASE IF NOT EXISTS `$database`");
$db->exec("use `$database`");

function tableExists($dbh, $id)
{
  $results = $dbh->query("SHOW TABLES LIKE '$id'");
  if(!$results) {
    return false;
  }
  if($results->rowCount() > 0) {
    return true;
  }
  return false;
}

$exists = tableExists($db, "events");

if (!$exists) {
  $db->exec("CREATE TABLE IF NOT EXISTS events (
                        id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL, 
                        name TEXT, 
                        start DATETIME NOT NULL, 
                        end DATETIME NOT NULL)");

  $items = array(
      array('name' => 'Event 1',
          'start' => '2019-10-09T00:00:00',
          'end' => '2013-10-09T10:00:00')
  );

  $insert = "INSERT INTO events (name, start, end) VALUES (:name, :start, :end)";
  $stmt = $db->prepare($insert);

  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':start', $start);
  $stmt->bindParam(':end', $end);

  foreach ($items as $it) {
    $name = $it['name'];
    $start = $it['start'];
    $end = $it['end'];
    $stmt->execute();
  }

}
