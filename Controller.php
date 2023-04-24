<?php
require_once './model/Diner.php';
require_once 'autoload.php';

class Controller
{
    private $twig;
    private $diner;
    private $action;
    
    public function __construct() {
        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);
        $this->diner = new Diner();
        $this->action = $this->getAction();
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
            case 'Sign Up':
                $this->processShowSignUp();
                break;
            case 'Register':
                $this->processShowRegister();
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
        $template = $this->twig->load('services.twig');
        echo $template->render();
    }
    
    /**
     * Handles the request to show the pricequote page
     */
    private function processShowPriceQuote() {
        $template = $this->twig->load('pricequote.twig');
        echo $template->render();
    }
    
    /**
     * Handles the request to show the signup page
     */
    private function processShowSignUp() {
        $template = $this->twig->load('signup.twig');
        echo $template->render();
    }
    
    /**
     * Handles the request to show the register page
     */
    private function processShowRegister() {
        $template = $this->twig->load('register.twig');
        echo $template->render();
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
}

