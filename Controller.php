<?php
require_once './model/Database.php';
require_once './model/Validator.php';
require_once './model/ParentsTable.php';
require_once './model/ServiceTable.php';
require_once 'autoload.php';

class Controller
{
    private $twig;
    private $action;
    private $validate;
    private $fields;
    private $db;
    
    public function __construct() {
        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);
        $this->setupConnection();
        $this->connectToDatabase();
        $this->twig->addGlobal('session', $_SESSION);
        $this->action = $this->getAction();
        $this->validate = new Validator();
        $this->fields = $this->validate->getFields();
        $this->fields->addField('username');
        $this->fields->addField('password');
        $this->fields->addField('firstName');
        $this->fields->addField('lastName');
        $this->fields->addField('email');
        $this->fields->addField('studentName');
        $this->fields->addField('grade');
        $this->fields->addField('subject');
        $this->fields->addField('location');
        }
    
    public function invoke() {
        switch($this->action) {
            case 'Home':
                $this->processShowHome();
                break;
            case 'Tutors':
                $this->processShowTutors();
                break;
            case 'Services':
                $this->processShowServices();
                break;
            case 'Price Quote':
                $this->processShowPriceQuote();
                break;
            case 'Show Register':
                $this->processShowRegister();
                break;
            case 'Register':
                $this->processRegister();
                break;
            case 'Show Login':
                $this->processShowLogin();
                break;
            case 'Login':
                $this->processLogin();
                break;
            case 'Show Sign Up':
                $this->processShowSignUp();
                break;
            case 'Sign Up':
                $this->processSignUp();
                break;
            case 'Logout':
                $this->processLogout();
                break;
            case 'Show Orders':
                $this->processShowOrders();
                break;
            default:
                $this->processShowHome();
                break;
        }
    }
    
    /**
     * Handles the request to show the home page
     */
    private function processShowHome() {
        $template = $this->twig->load('home.twig');
        echo $template->render();
    }
    
    /**
     * Handles the request to show the tutors page
     */
    private function processShowTutors() {
        $template = $this->twig->load('tutors.twig');
        echo $template->render();
    }
    
    /**
     * Handles the request to show the services page
     */
    private function processShowServices() {
        $ServiceTable = new ServiceTable($this->db);
        $subjects = $ServiceTable->get_subjects();
        $locations = $ServiceTable->get_locations();
        $levels = $ServiceTable->get_levels();
        $template = $this->twig->load('services.twig');
        echo $template->render(['subjects' => $subjects, 'locations' => $locations, 'levels' => $levels]);
    }
    
    /**
     * Handles the request to show the pricequote page
     */
    private function processShowPriceQuote() {
        $template = $this->twig->load('pricequote.twig');
        echo $template->render();
    }
    
    /**
     * Handles the request to show the register page
     */
    private function processShowRegister() {
        $username = '';
        $password = '';
        $firstName = '';
        $lastName = '';
        $email = '';
        $error_username = '';
        $error_password = '';
        $error_firstName = '';
        $error_lastName = '';
        $error_email = '';
        $template = $this->twig->load('register.twig');
        echo $template->render(['username' => $username, 'password' => $password, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'error_username' => $error_username, 'error_password' => $error_password, 'error_firstName' => $error_firstName, 'error_lastName' => $error_lastName, 'error_email' => $error_email]);
    }
    
    /**
     * Handles the request to Register new user
     */
    private function processRegister() {
        $parentID = '';
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email');
        $error_username = '';
        $error_password = '';
        $error_firstName = '';
        $error_lastName = '';
        $error_email = '';
        
        $this->validate->checkText('username', $username);
        $this->validate->checkPassword('password', $password, $required = true);
        $this->validate->checkText('firstName', $firstName);
        $this->validate->checkText('lastName', $lastName);
        $this->validate->checkEmail('email', $email);
        
        $error = $this->fields->hasErrors();
        
        if($error) {
            $error_username = $this->fields->getField('username')->getHTML();
            $error_password = $this->fields->getField('password')->getHTML();
            $error_firstName = $this->fields->getField('firstName')->getHTML();
            $error_lastName = $this->fields->getField('lastName')->getHTML();
            $error_email = $this->fields->getField('email')->getHTML();
            $template = $this->twig->load('register.twig');
            echo $template->render(['username' => $username, 'password' => $password, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'error_username' => $error_username, 'error_password' => $error_password, 'error_firstName' => $error_firstName, 'error_lastName' => $error_lastName, 'error_email' => $error_email]);
        } else {
            $ParentsTable = new ParentsTable($this->db);
            $ParentsTable->addUser($parentID, $username, $password, $firstName, $lastName, $email);
            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username;
            $this->processLogin();
        }
    }
    
    /**
     * Handles the request to show the login page
     */
    private function processShowLogin() {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $error_username = '';
        $error_password = '';
        $template = $this->twig->load('login.twig');
        echo $template->render(['username' => $username, 'password' => $password, 'error_username' => $error_username, 'error_password' => $error_password]);
    }
    
    /**
     * Logs in the user with the credentials specified in the post array
     */
    private function processLogin() {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $ParentsTable = new ParentsTable($this->db);
        
        if ($ParentsTable->isValidUserLogin($username, $password)) {
            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username;
            $this->processShowSignUp();
        } else {
            $login_message = 'Invalid username or password';
            $template = $this->twig->load('login.twig');
            echo $template->render(['login_message' => $login_message]);
        }
    }
    
    /**
     * Handles the request to show the signup page
     */
    private function processShowSignUp() {
        $ParentsTable = new ParentsTable($this->db);
        $firstName = implode(" ",$ParentsTable->get_parent_name($_SESSION['username']));
        $ServiceTable = new ServiceTable($this->db);
        $subjects = $ServiceTable->get_subjects();
        $locations = $ServiceTable->get_locations();
        $levels = $ServiceTable->get_levels();
        $template = $this->twig->load('signup.twig');
        echo $template->render(['subjects' => $subjects, 'locations' => $locations, 'levels' => $levels, 'firstName' => $firstName]);
    }
    
    /**
     * Handles the request to signup for service
     */
    private function processSignUp() {
        $ParentsTable = new ParentsTable($this->db);
        $studentName = filter_input(INPUT_POST, 'studentName');
        $grade = filter_input(INPUT_POST, 'grade');
        $subject = filter_input(INPUT_POST, 'subject');
        $location = filter_input(INPUT_POST, 'location');
        $firstName = implode(" ",$ParentsTable->get_parent_name($_SESSION['username']));
        $ServiceTable = new ServiceTable($this->db);
        $subjects = $ServiceTable->get_subjects();
        $locations = $ServiceTable->get_locations();
        $levels = $ServiceTable->get_levels();      
        
        $this->validate->checkText('studentName', $studentName);
        $this->validate->checkDropdown('grade', $grade);
        $this->validate->checkDropdown('subject', $subject);
        $this->validate->checkRadio('location', $location);
        $error = $this->fields->hasErrors();

        if($error) {
            $error_student = $this->fields->getField('studentName')->getHTML();
            $error_level = $this->fields->getField('grade')->getHTML();
            $error_subject = $this->fields->getField('subject')->getHTML();
            $error_location = $this->fields->getField('location')->getHTML();
            $template = $this->twig->load('signup.twig');
            echo $template->render(['subjects' => $subjects, 'locations' => $locations, 'levels' => $levels, 'firstName' => $firstName, 'studentName' => $studentName, 'grade' => $grade, 'location' => $location, 'subject' => $subject, 'error_student' => $error_student, 'error_level' => $error_level, 'error_subject' => $error_subject, 'error_location' => $error_location]);
        } else {
            $this->processShowHome();
        }
       
    }
    
    /**
     * Clears all session data from memory and cleans up the session ID
     * in order to logout the current user
     */
    private function processLogout() {
        $_SESSION = array();
        session_destroy();
        $login_message = 'You have been logged out.';
        $template = $this->twig->load('login.twig');
        echo $template->render(['login_message' => $login_message]);
        header("Location: .?action=Show Login");

    }
    
    /**
     * Shows the orders of the logged in user. If no user is logged in,
     * shows the login page
     */
    private function processShowOrders() {
        if (!isset($_SESSION['is_valid_user'])) {
            $login_message = 'Log in to manage your tasks.';
            $template = $this->twig->load('login.twig');
            echo $template->render(['login_message' => $login_message]);
        } else {
            $errors = array();
            $template = $this->twig->load('services.twig');
            echo $template->render(['errors' => $errors, 'tasks' => $tasks]);
        }
    }
    
    /**
     * Gets the action from $_GET or $_POST array
     */
    private function getAction() {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($action === NULL) {
            $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($action === NULL) {
                $action = '';
            }
        }
        return $action;
    }
    
    /**
     * Ensures a secure connection and start session
     */
    private function setupConnection() {
        $https = filter_input(INPUT_SERVER, 'HTTPS');
        if (!$https) {
            $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
            $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
            $url = 'https://' . $host . $uri;
            header("Location: " . $url);
            exit();
        }
        session_start();
    }
    
    /**
     * Connects to the database
     */
    private function connectToDatabase() {
        $this->db = new Database();
        if (!$this->db->isConnected()) {
            $error_message = $this->db->getErrorMessage();
            $template = $this->twig->load('database_error.twig');
            echo $template->render(['error_message' => $error_message]);
            exit();
        }
    }
}

