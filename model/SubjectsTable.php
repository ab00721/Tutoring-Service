<?php
require_once 'Database.php';

class SubjectsTable {
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
        return $subject;
    }

}