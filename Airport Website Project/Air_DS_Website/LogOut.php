<!-- This php file only exist to delete the session once the user Logs out -->
 
<?php

    session_start();

    session_destroy();

    header("Location:Home.php");

?>