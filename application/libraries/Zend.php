<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }
 
class CI_Zend {
 
    /**
     * This constructor check if the required class is not already loaded and do a report in log
     * file of code igniter
     *
     * @param <type> $class
     */
    public function __construct($class = NULL) {
 
        ini_set('include_path',ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries');
 
        if ($class) {
 
            require_once (string) $class.'.php';
            log_message('debug', "Zend Class $class Loaded");
 
        }else {
 
            log_message('debug', "Zend Class Initialized");
 
        }
    }
 
    /**
     * This function loads the requested Zend class.
     *
     * @param <type> $sClassName
     */
    public function load($sClassName) {
 
        require_once (string) $sClassName.'.php';
 
        log_message('debug', "-> Zend Class $sClassName Loaded from the library");
 
    }
 
}