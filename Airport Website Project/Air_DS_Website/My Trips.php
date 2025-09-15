<?php

session_start();

if(!isset($_SESSION["CorrectUsername"]) || !isset($_SESSION["CorrectPassword"]))
{
    //Redirects to Home if the user is not Logged in
    header("Location:Login.php");
}

$Username = $_SESSION['CorrectUsername'];

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

if(($_SERVER["REQUEST_METHOD"] == "POST"))
{

    $Departure = $_POST['Departure'];

    if( trim($Departure) == "Athens International Airport")
    {
        $Departure = 'Athens International Airport "Eleftherios Venizelos"'; // This happens because the htm, when recieving port variables, cannot recognize anything inside "". So it deletes "Eleftherios Venizelos". I make sure that the name stays as it is
    }

    $Arrival = $_POST['Arrival'];

    if( trim($Arrival) == "Athens International Airport")
    {
        $Arrival = 'Athens International Airport "Eleftherios Venizelos"'; // Same as Departure
    }

    $Date = $_POST['Date'];
    $PassengerNo = (int) $_POST['PassengerNo'];

    //Searching for Name and Username
    $sql = " SELECT * FROM users WHERE Username ='$Username'";
    $result = $conn->query($sql);
        
    if ($result->num_rows > 0 )
    {
        while($row = $result->fetch_assoc())
        {
            $Name = $row["Name"];
            $Surname = $row["Surname"];
        }

    }

    $PassInfo[] = [$Name,$Surname,$_POST['P1'],$_POST['CC1']];

    for ($i=2; $i<=$PassengerNo; $i++)
    {
        $PassInfo[] = [$_POST['PName'.$i],$_POST['PSurname'.$i],$_POST['P'.$i],$_POST['CC'.$i]];
    }

    //Calculating Tax for the total of reservations

    //Searching for Airport tax, Latitude and Longitude
    $sql = " SELECT * FROM airports WHERE Name ='$Departure'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0 )
    {
        while($row = $result->fetch_assoc())
        {
            $Tax1 = $row["Tax"];
            $Lat1 = $row["Latitude"];
            $Long1 = $row["Longitude"];
        }

    }

    $sql = " SELECT * FROM airports WHERE Name ='$Arrival'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0 )
    {
    
        while($row2 = $result->fetch_assoc())
        {
            $Tax2 = $row2["Tax"];
            $Lat2 = (float)$row2["Latitude"];
            $Long2 = (float)$row2["Longitude"];
        }

    }

    $Tax = $Tax1 + $Tax2;

    //d = 2R × sin⁻¹(√[sin²((θ₂ - θ₁)/2) + cosθ₁ × cosθ₂ × sin²((φ₂ - φ₁)/2)])
    $R = 6371.0;

    $d = 2*$R * asin( sqrt( pow( sin( ( $Lat2 - $Lat1)/2 ), 2 ) + cos($Lat1)*cos($Lat2)*pow(sin(($Long2 - $Long1)/2 ),2)) );

    $FlightCost = $d / 10;

    $TicketCost =$Tax + $FlightCost;

    for ($i=0; $i<=$PassengerNo-1; $i++)
    {
        $TicketCost+=(Float)$PassInfo[$i][3];
    }

    $FinalTax= $PassengerNo*$TicketCost;

    //Sending Reservation details to database

        //Generating an id first
        $idfound = false;

        while(!$idfound)
        {
            $id = "";
            $Symbols = "aAbBcCdEeFfGgHhIiJjKkLlMmNnoOPpQqRr1234567890";

            for($i = 0; $i <=4; $i++)
            {
                $id = $id.($Symbols[random_int(0,strlen("aAbBcCdEeFfGgHhIiJjKkLlMmNnoOPpQqRr1234567890")-1)]);
            }

            $sql = " SELECT * FROM reservations WHERE id ='$id'";
            $result = $conn->query($sql);

            if (!($result->num_rows > 0) )
            {
                $idfound = true;
            }
        }

    for ($i=0; $i<=$PassengerNo-1; $i++)
    {
        $N=$PassInfo[$i][0]."";
        $Sur=$PassInfo[$i][1]."";
        $se=$PassInfo[$i][2]."";
        $seC=$PassInfo[$i][3]."";

        $sql = " SELECT * FROM reservations WHERE Username ='$Username'AND Name='$N'AND Surname= '$Sur'AND Departure= '$Departure'AND Arrival= '$Arrival'AND Seat= '$se'AND Date='$Date'AND Tax ='$FinalTax'";
        $results = $conn->query($sql);

        if (!($results->num_rows > 0) )
        {
            $sql = "INSERT INTO reservations (id,Username, Name,Surname, Departure, Arrival, Seat, SeatCost,Date, Tax) VALUES ('$id','$Username' ,'$N','$Sur' ,'$Departure' ,'$Arrival' ,'$se','$seC','$Date', '$FinalTax')";

            if ($conn->query($sql) === True);
    
        }

    }
}

//Fetching all Reservations from database

$ReservID = [];

$sql = " SELECT DISTINCT id FROM reservations WHERE Username ='$Username'";
$results = $conn->query($sql);

if ($results->num_rows > 0 )
{
    while($reservid = $results->fetch_assoc())
    {
        $ReservID[] = $reservid;
    }
}

$Today = new DateTime();
$Today->modify('+30 days');

$Future = $Today->format('Y-m-d');

//Taking all the ids this user has booked till now

$sql = " SELECT id FROM reservations WHERE Username = '$Username' AND Date <'$Future'";
$results = $conn->query($sql);
$ids=[];

if ($results->num_rows > 0 )
{
    while($reservations = $results->fetch_assoc())
    {
        $ids[] = $reservations['id'];
    }

}


?>

<!DOCTYPE html>
<html lang="en">

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="My Trips.css?v=<?php echo time(); ?>">
</head>

<body>

    <Bar>
        <titlle> FLYING WITH AIR</titlle><p>DS</p>      
    </Bar>

    <section>
        <p> My planned Trips</p>
        <note>You cannot cancel your flight <br>if its departure date is within 30 days from today</note>

        <trips>

            <?php

                // If there are not any reservations then it apears a text that says "No booked trips yet"

                if(empty($ReservID))
                {
                    echo "<Reservation style='color:white;font-size:20px; padding:20px;'>";
                        echo "No booked trips yet";
                    echo "</Reservation>";  
                }

                //If there are reservations then, it outpust the information for each reserv.

                foreach ($ReservID as $reservId) 
                {
                    $curid=$reservId['id'];               
                    $reservation = [];
                    $Reservation = [];

                    $sql = " SELECT * FROM reservations WHERE id ='$curid'";
                    $results = $conn->query($sql);

                    if ($results->num_rows > 0 )
                    {
                        while($reservations = $results->fetch_assoc())
                        {
                            $reservation[] = $reservations;
                        }

                    }  


                    echo "<Reservation>";

                    //This foreach includes all the passengers of the spesific reserv

                    foreach ($reservation as $Reservation) 
                    {
                        echo "<Pass>";  

                        echo "<name>";
                        echo "Passenger's Name <br>";
                        echo "<p>".$Reservation['Name']." ".$Reservation['Surname']."</p>";
                        echo "</name>";

                        echo "<departure>";
                        echo "Depart from <br>";
                        echo "<p>".$Reservation['Departure']."</p>";
                        echo "</departure>";

                        echo "<arrival>";
                        echo "Arrive at <br>";
                        echo "<p>".$Reservation['Arrival']."</p>";
                        echo "</arrival>";

                        echo "<date>";
                        echo "Flight Date <br>";
                        echo "<p>".$Reservation['Date']."</p>";
                        echo "</date>";

                        echo "<seat>";
                        echo "Seat Code and Cost <br>";
                        echo "<p>".$Reservation['Seat']." "."(".$Reservation['SeatCost']."€)</p>";
                        echo "</seat>";

                        //echo "<Tax>";
                        //echo "Total Reservation Tax <br>";
                        //echo "<p>".$Reservation['Tax']."</p>";
                       // echo "</Tax>";

                        echo "</Pass>";  

                    }

                    $Rid=$Reservation['id'];
                    $RTax=$Reservation['Tax'];

                    echo "<p>Final Cost : <Cost style='color:white;'> $RTax €</Cost> </p>";

                    if (in_array($Rid,$ids,true))
                    {
                        echo "<button name='$Rid' name='$Rid' onclick='window.location.href=\"TripCancelation.php?id=$Rid\"'style='background-color:rgb(121, 123, 126); color:white;' disabled >Cancel</button>";
                    }
                    else
                    {
                        echo "<button name='$Rid' name='$Rid' onclick='window.location.href=\"TripCancelation.php?id=$Rid\"'>Cancel</button>";
                    }

                    echo "</Reservation>";  
                   
                }

            ?>

        </trips>

    </section>

    <button onclick='window.location.href="Home.php"'>Home</button>

    <footer>

        <p> Contact Number : 210 3530000 <br><br> Contact Email : Airdsairlines@gmail.com</p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12586.198840455869!2d23.937224695673063!3d37.9409487204273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14a1901ad9e75c61%3A0x38b215df0aeeb3aa!2zzpTOuc61zrjOvc6uz4IgzpHOtc-Bzr_Ou865zrzOrc69zrHPgiDOkc64zrfOvc-Ozr0gzpXOu861z4XOuM6tz4HOuc6_z4IgzpLOtc69zrnOts6tzrvOv8-C!5e0!3m2!1sel!2sgr!4v1747322533807!5m2!1sel!2sgr" width="160" height="160" allowfullscreen=""  referrerpolicy="no-referrer-when-downgrade" style="border:solid; border-width: 2px; border-color:rgb(92, 159, 217); border-radius:10px;"></iframe>
    
    </footer>

</body>

</html>