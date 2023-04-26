<?php
require_once 'Database.php';

class ParentsTable {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
        
    function get_parent($parentID) {
        $query = 'SELECT * FROM parents
              WHERE parentID = :parentID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':parentID', $parentID);
        $statement->execute();
        $parent = $statement->fetch();
        $statement->closeCursor();
        return $parent;
    }
}
