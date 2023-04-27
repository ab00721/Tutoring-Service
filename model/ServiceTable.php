<?php
require_once 'Database.php';

class ServiceTable {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    function get_subjects() {
        $query = 'SELECT * FROM subjects;';
        $statement = $this->db->getDB()->prepare($query);
        $statement->execute();
        $subjects = $statement->fetchAll();
        $statement->closeCursor();
        return $subjects;  
    }
        
    function get_subject($subjectID) {
        $query = 'SELECT * FROM subjects
              WHERE subjectID = :subjectID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':subjectID', $subjectID);
        $statement->execute();
        $subject = $statement->fetch();
        $statement->closeCursor();
        return $subject[1];
    }
    
    function get_locations() {
        $query = 'SELECT * FROM locations;';
        $statement = $this->db->getDB()->prepare($query);
        $statement->execute();
        $locations = $statement->fetchAll();
        $statement->closeCursor();
        return $locations;  
    }
        
    function get_location($locationID) {
        $query = 'SELECT * FROM locations
              WHERE locationID = :locationID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':locationID', $locationID);
        $statement->execute();
        $location = $statement->fetch();
        $statement->closeCursor();
        return $location[1];
    }
    
    function get_levels() {
        $query = 'SELECT * FROM levels;';
        $statement = $this->db->getDB()->prepare($query);
        $statement->execute();
        $levels = $statement->fetchAll();
        $statement->closeCursor();
        return $levels;  
    }
        
    function get_level($levelID) {
        $query = 'SELECT * FROM levels
              WHERE levelID = :levelID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':levelID', $levelID);
        $statement->execute();
        $level = $statement->fetch();
        $statement->closeCursor();
        return $level[1];
    }

}