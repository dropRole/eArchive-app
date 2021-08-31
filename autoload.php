<?php 

// register class autoloader
spl_autoload_register(function ($class) {
        require_once "{$class}.php";
});

?>