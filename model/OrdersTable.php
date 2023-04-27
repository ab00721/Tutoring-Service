<?php
require_once 'Database.php';

class OrdersTable {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function addOrder($orderID, $parentID, $studentName, $subject, $location, $level) {
        $query = 'INSERT INTO orders (orderID, parentID, studentName, subject, location, level)
              VALUES (:orderID, :parentID, :studentName, :subject, :location, :level)';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':orderID', $orderID);
        $statement->bindValue(':parentID', $parentID);
        $statement->bindValue(':studentName', $studentName);
        $statement->bindValue(':subject', $subject);
        $statement->bindValue(':location', $location);
        $statement->bindValue(':level', $level);
        $statement->execute();
        $statement->closeCursor();
    }
    
    public function getOrders($parentID) {
        $query = 'SELECT * FROM orders
              WHERE parentID = :parentID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':parentID', $parentID);
        $statement->execute();
        $orders = $statement->fetchAll();
        $statement->closeCursor();
        return $orders;
    }
    
}
