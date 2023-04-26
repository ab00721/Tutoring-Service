<?php
require_once 'Database.php';

class OrdersTable {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function addOrder($orderID, $studentID, $subjectID, $locationID, $levelID) {
        $query = 'INSERT INTO orders (orderID, studentID, subjectID, locationID, levelID)
              VALUES (:orderID, :studentID, :subjectID, :locationID, :levelID)';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':orderID', $orderID);
        $statement->bindValue(':studentID', $studentID);
        $statement->bindValue(':subjectID', $subjectID);
        $statement->bindValue(':locationID', $locationID);
        $statement->bindValue(':levelID', $levelID);
        $statement->execute();
        $statement->closeCursor();
    }
    
}
