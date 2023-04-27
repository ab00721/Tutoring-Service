<?php
require_once 'Database.php';

class StudentsTable {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
          
    /**
     * Checks if the specified student already exists 
     */
    public function getStudent($parentID, $studentName) {
        $query = 'SELECT * FROM students 
                    WHERE parentID = :parentID
                    AND studentName = :studentName';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':parentID', $parentID);
        $statement->bindValue(':studentName', $studentName);
        $statement->execute();
        $student = $statement->fetch();
        $statement->closeCursor();
        return $student;
    }
    
    /**
     * Checks if the specified student already exists 
     */
    public function getStudents($parentID) {
        $query = 'SELECT * FROM students 
                    WHERE parentID = :parentID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':parentID', $parentID);
        $statement->execute();
        $student = $statement->fetch();
        $statement->closeCursor();
        return $student;
    }
    
    /**
     * Checks if the specified student already exists 
     */
    public function getStudentByID($studentID) {
        $query = 'SELECT * FROM students 
                    WHERE studentID = :studentID';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':studentID', $studentID);
        $statement->execute();
        $student = $statement->fetch();
        $statement->closeCursor();
        return $student;
    }
    
    /**
     * Adds the specified student to the table parents
     */
    public function addStudent($parentID, $studentID, $studentName) {
        $query = 'INSERT INTO students (parentID, studentID, studentName)
              VALUES (:parentID, :studentID, :studentName)';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':parentID', $parentID);
        $statement->bindValue(':studentID', $studentID);
        $statement->bindValue(':studentName', $studentName);
        $statement->execute();
        $statement->closeCursor();
    }
    
}
