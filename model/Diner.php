<?php
class Diner { 
    
    /**
     * Gets the hours
     *
     * @return array with hours 
     */
    public function getHours() {
       
    }
    
    /**
     * Gets the menu
     *
     * @return array with menu items
     */
    public function getMenu() {
        $menu = array(
            array('name' => 'Cheeseburger', 'price' => 4.99), 
            array('name' => 'Hamburger', 'price' => 5.99), 
            array('name' => 'French Fries', 'price' => 2.99), 
            array('name' => 'Caesar Salad', 'price' => 5.99)
        );
        return $menu;
    }
    
    /**
     * Gets the weekly special 
     *
     * @return string with special of the week
     */
    public function getSpecial() {
        return 'Any burger with a drink of your choice only $5 this week!';
    }
}
?>