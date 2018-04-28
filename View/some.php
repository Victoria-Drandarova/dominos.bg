<?php 
session_start();
//session_destroy();

echo '<pre>'. print_r($_SESSION, true) .'</pre>';