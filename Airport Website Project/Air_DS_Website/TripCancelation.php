<!-- This php file only exist to delete the trip from data base once the user cancels a trip -->

<?php
    session_start();

    if(!isset($_SESSION["CorrectUsername"]) || !isset($_SESSION["CorrectPassword"]))
    {
        header("Location:Login.php");
    }

    //Naming all the needed parameters
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "air_ds";

    //Connecting
    $conn = new mysqli($server, $username, $password, $dbname);

    //Checking if connected successfully
    if($conn->connect_error)
    {
        die("Connection failed: " . $con->connect_error);
    }

    if(($_SERVER["REQUEST_METHOD"] == "GET"))
    {

        $Id = $_GET['id'];
        echo $Id;
        $sql = " DELETE FROM reservations WHERE id ='$Id'";
        $results = $conn->query($sql);

        header("Location:My Trips.php");
        exit();
    }

?>