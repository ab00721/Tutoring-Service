<?php
require_once 'Database.php';

class ParentsTable {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
        
    function get_parent_name($username) {
        $query = 'SELECT firstName FROM parents
              WHERE username = :username';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $parent = $statement->fetch();
        $statement->closeCursor();
        return $parent;
    }
    
    function get_parent($username) {
        $query = 'SELECT * FROM parents
              WHERE username = :username';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $parent = $statement->fetch();
        $statement->closeCursor();
        return $parent;
    }
    
    function get_parent_by_email($email) {
        $query = 'SELECT * FROM parents
              WHERE email = :email';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $parent = $statement->fetch();
        $statement->closeCursor();
        return $parent;
    }
    
    /**
     * Checks if the specified username is in this database
     * 
     * @param string $username
     * @return boolean - true if username is in this database
     */
    public function isValidUser($username) {
        $query = 'SELECT * FROM parents
              WHERE username = :username';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        return !($row === false);
    }
    
    /**
     * Adds the specified user to the table parents
     * 
     * @param type $parentID
     * @param type $username
     * @param type $password
     * @param type $firstName
     * @param type $lastName
     * @param type $email
     */
    public function addUser($parentID, $username, $password, $firstName, $lastName, $email) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = 'INSERT INTO parents (parentID, username, password, firstName, lastName, email)
              VALUES (:parentID, :username, :hash, :firstName, :lastName, :email)';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':parentID', $parentID);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':hash', $hash);
        $statement->bindValue(':firstName', $firstName);
        $statement->bindValue(':lastName', $lastName);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $statement->closeCursor();
    }
    
    /**
     * Checks the login credentials
     * 
     * @param type $username
     * @param type $password
     * @return boolen - true if the specified password is valid for the 
     *              specified username
     */
    public function isValidUserLogin($username, $password) {
        $query = 'SELECT password FROM parents
              WHERE username = :username';
        $statement = $this->db->getDB()->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        if ($row === false) {
            return false;
        }
        $hash = $row['password'];
        return password_verify($password, $hash);
    }
    
}
