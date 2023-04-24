<?php
class Database {
    private $db;
    private $error_message;
    
    /**
     * Instantiates a new database object that connects
     * to the database
     */
    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=tutor_manager';
        $username = 'tutor';
        $password = 'tutor';
        $this->error_message = '';
        try {
            $this->db = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();
        }
    }
    
    /**
     * Checks the connection to the database
     *
     * @return boolean - true if a connection to the database has been established
     */
    public function isConnected() {
        return ($this->db != Null);
    }
    
    /**
     * Returns the error message
     * 
     * @return string - the error message
     */
    public function getErrorMessage() {
        return $this->error_message;
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
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        return !($row === false);
    }
    
    /**
     * Adds the specified user to the table users
     * 
     * @param type $username
     * @param type $password
     */
    public function addUser($username, $password, $firstName, $lastName, $email) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = 'INSERT INTO parents (username, password, firstName, lastName, email)
              VALUES (:username, :password, :firsName, :lastName, :email)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hash);
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
        $statement = $this->db->prepare($query);
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
?>