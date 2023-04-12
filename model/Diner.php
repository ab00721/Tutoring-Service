<?php
class Diner { 
    
    /**
     * Gets the hours
     *
     * @return array with hours 
     */
    public function getHours() {
       $hours = array(
            array('day' => 'Sunday', 'times' => 'closed'), 
            array('day' => 'Monday', 'times' => 'closed'), 
            array('day' => 'Tuesday', 'times' => '11am - 2pm'), 
            array('day' => 'Wednesday', 'times' => '11am - 2pm'),
            array('day' => 'Thursday', 'times' => '11am - 2pm'), 
            array('day' => 'Friday', 'times' => '11am - 2pm'), 
            array('day' => 'Saturday', 'times' => '11am - 2pm')
        );
        return $hours;
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