<?php 

// register class autoloader
spl_autoload_register(function ($class) {
    // if it's extended PDO class
    if ($class === 'DBC')
        require_once "{$class}.php";
    else
        include_once "{$class}.php";
});

?>