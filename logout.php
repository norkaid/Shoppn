<?php

session_start();

session_unset();

session_destroy();

header(
    'Location: http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php'
);

exit();

?>