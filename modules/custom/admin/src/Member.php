<?php

namespace Drupal\admin;

use Drupal\Core\Database\Connection;

/**
* Defines a storage handler class that handles the node grants system.
*
* This is used to build node query access.
*
* @ingroup admin
*/
class Member {

 /**
  * The database connection.
  *
  * @var \Drupal\Core\Database\Connection
  */
 protected $database;

 /**
  * Constructs a Member object.
  *
  * @param \Drupal\Core\Database\Connection $database
  *   The database connection.
  */
 // The $database variable came to us from the service argument.
 public function __construct(Connection $database) {
   $this->database = $database;
 }

 /**
  * Add new record in table members.
  */
 public function add($username, $address) {
   if (empty($username) || empty($address)) {
    return FALSE;
   }
   // Example of working with DB in Drupal 8.
   $query = $this->database->insert('members');
   $query->fields(array(
    'username' => $username,
    'address' => $address,
   ));
   return $query->execute();
 }

 /**
  * Get all records from table members.
  */
 public function getAll() {
   return $this->getById();
 }

 /**
  * Get records by id from table members.
  */
 public function getById($id = NULL, $reset = FALSE) {
   $query = $this->database->select('members');
   $query->fields('members', array('id', 'username', 'address'));
   if ($id) {
    $query->condition('id', $id);
   }
   $result = $query->execute()->fetchAll();
   if (count($result)) {
    if ($reset) {
    $result = reset($result);
    }
    return $result;
   }
   return FALSE;
 }

}
