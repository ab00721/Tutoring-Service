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
            case 'Hours':
                $this->processShowHours();
                break;
            case 'Menu':
                $this->processShowMenu();
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
     * Handles the request to show the hours page
     */
    private function processShowHours() {
        $hours = $this->diner->getHours();
        $template = $this->twig->load('hours.twig');
        echo $template->render(['hours' => $hours]);
    }
    
    /**
     * Handles the request to show the menu page
     */
    private function processShowMenu() {
        $menu = $this->diner->getMenu();
        $special = $this->diner->getSpecial();
        $template = $this->twig->load('menu.twig');
        echo $template->render(['special' => $special, 'menu' => $menu]);
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

