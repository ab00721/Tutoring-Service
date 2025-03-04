<?php
require_once 'Fields.php';

class Validator {
    private $fields;

    public function __construct() {
        $this->fields = new Fields();
    }

    public function getFields() {
        return $this->fields;
    }

    public function foundErrors() {
        return $this->fields->hasErrors();
    }
    
    public function addField($name, $message = '') {
        return $this->fields->addField($name, $message);
    }
    
    // Validate a generic text field
    public function checkText($name, $value,
            $required = true, $min = 1, $max = 51) {

        // Get Field object
        $field = $this->fields->getField($name);

        // If field is not required and empty, remove error and exit
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }

        // Check field and set or clear error message
        if ($required && empty($value)) {
            $field->setErrorMessage('Required.');
        } else if (strlen($value) < $min) {
            $field->setErrorMessage('Too short.');
        } else if (strlen($value) > $max) {
            $field->setErrorMessage('Too long.');
        } else {
            $field->clearErrorMessage();
        }
    }

    // Validate a field with a generic pattern
    public function checkPattern($name, $value, $pattern, $message,
            $required = true) {

        // Get Field object
        $field = $this->fields->getField($name);

        // If field is not required and empty, remove errors and exit
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }

        // Check field and set or clear error message
        $match = preg_match($pattern, $value);
        if ($match === false) {
            $field->setErrorMessage('Error testing field.');
        } else if ( $match != 1 ) {
            $field->setErrorMessage($message);
        } else {
            $field->clearErrorMessage();
        }
    }

    public function checkPostalCode($name, $value, $required = true, $min = 1, $max = 21) {
        $field = $this->fields->getField($name);
        
        // If field is not required and empty, remove error and exit
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }

        // Check field and set or clear error message
        if ($required && empty($value)) {
            $field->setErrorMessage('Required.');
        } else if (strlen($value) < $min) {
            $field->setErrorMessage('Too short.');
        } else if (strlen($value) > $max) {
            $field->setErrorMessage('Too long.');
        } else {
            $field->clearErrorMessage();
        }  
    }
    
    public function checkPhone($name, $value, $required = false) {
        $field = $this->fields->getField($name);

        // Call the text method and exit if it yields an error
        $this->checkText($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method to validate a phone number
        $pattern = '/^\(\d{3}\) \d{3}-\d{4}$/';
        $message = 'Use (999) 999-9999 format.';
        $this->checkPattern($name, $value, $pattern, $message, $required);
    }

    
    public function checkEmail($name, $value, $required = true) {
        $field = $this->fields->getField($name);

        // If field is not required and empty, remove errors and exit
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }

        // Call the text method and exit if it yields an error
        $this->checkText($name, $value, $required);
        if ($field->hasError()) { return; }

        // Split email address on @ sign and check parts
        $parts = explode('@', $value);
        if (count($parts) < 2) {
            $field->setErrorMessage('At sign required.');
            return;
        }
        if (count($parts) > 2) {
            $field->setErrorMessage('Only one at sign allowed.');
            return;
        }
        $local = $parts[0];
        $domain = $parts[1];

        // Check lengths of local and domain parts
        if (strlen($local) > 64) {
            $field->setErrorMessage('Username part too long.');
            return;
        }
        if (strlen($domain) > 255) {
            $field->setErrorMessage('Domain name part too long.');
            return;
        }

        // Patterns for address formatted local part
        $atom = '[[:alnum:]_!#$%&\'*+\/=?^`{|}~-]+';
        $dotatom = '(\.' . $atom . ')*';
        $address = '(^' . $atom . $dotatom . '$)';

        // Patterns for quoted text formatted local part
        $char = '([^\\\\"])';
        $esc  = '(\\\\[\\\\"])';
        $text = '(' . $char . '|' . $esc . ')+';
        $quoted = '(^"' . $text . '"$)';

        // Combined pattern for testing local part
        $localPattern = '/' . $address . '|' . $quoted . '/';

        // Call the pattern method and exit if it yields an error
        $this->checkPattern($name, $local, $localPattern,
                'Invalid username part.');
        if ($field->hasError()) { return; }

        // Patterns for domain part
        $hostname = '([[:alnum:]]([-[:alnum:]]{0,62}[[:alnum:]])?)';
        $hostnames = '(' . $hostname . '(\.' . $hostname . ')*)';
        $top = '\.[[:alnum:]]{2,6}';
        $domainPattern = '/^' . $hostnames . $top . '$/';

        // Call the pattern method
        $this->checkPattern($name, $domain, $domainPattern,
                'Invalid domain name part.');
    }
    
    public function checkPassword($name, $value, $required = true) {
        $field = $this->fields->getField($name);

        // Call the text method and exit if it yields an error
        $this->checkText($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method to validate a password
        $pattern = '/^(?=.*[[:digit:]])(?=.*[[:upper:]])(?=.*[[:lower:]])[[:print:]]{6,21}$/';
        $message = 'Password must be between 6 and 21 characters. Password must contain at least one lower case letter, one uppercase letter, and one digit.';
        $this->checkPattern($name, $value, $pattern, $message, $required);
    }
    
    public function checkDropdown($name, $value, $required = true) {
        $field = $this->fields->getField($name);
        $pattern = '/^[[:digit:]]$/';
        $message = 'Please select an option from the dropdown.';
        $this->checkPattern($name, $value, $pattern, $message, $required);
    }
    
    public function checkRadio($name, $value, $required = true) {
        $field = $this->fields->getField($name);
        $pattern = '/^[[:digit:]]$/';
        $message = 'Please select an option from the radio buttons.';
        $this->checkPattern($name, $value, $pattern, $message, $required);
    }

}
?>