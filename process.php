<?php

session_start(); //This allows sessions to be created with this file allowing us to store date across pages
//echo"Process.php page <br>";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //echo"POST received";
    /*
    $_SERVER is variable that contains information about server (request method, server name etc.)
    We are checking if the request method is POST if yes then we extract some variables from the POST request
    */
    $process_function = $_POST['process_function'];
    /*
    We want to use this process.php as a backend for all other functions. So every time there is a HTML form submitted we want to all this process.php file and tell it to do something
    We need to identify which function to call therefore we store a hidden input in the HTML code where we specify which process_function we want to use with that form and then we pass that
    process_function as a variable into a switch function
    */
    switch($process_function){
        case 'ip_validation':
            $IP_string = $_POST["IP_string"];
            /*
            If the process_function variable is ip_validation we will create a $_SESSION variable where we store the result which will be later passed over to HTML page
            */
            if(ValidateIP($IP_string)){
                $_SESSION['result'] = "IP is correct";
                header("Location: ipvalidation.php");
            }
            else{
                $_SESSION['result'] = "IP is INcorrect";
                header("Location: ipvalidation.php");
            };
            break;
        
        case 'check_if_private':
            $IP_string = $_POST["IP_string"];
            if(ValidateIP($IP_string) != true){
                $_SESSION['result'] = "IP is NOT correct";
                header("Location: ipvalidation.php");
                break;
            }
            check_if_private($IP_string);
            header("Location: ipvalidation.php");
            break;
        
        case 'register':
            //we check for the process_function
            $user = $_POST['username'];
            $password = $_POST['password'];
            //we extract the username and password atribute and store in into variables
            $user_priv = $_POST['user_priv'];
            //we extract user_priv value to see if the user should be admin
            $_SESSION['user_created'] = register_user($user,$password,$user_priv);
            //we will execute the register_user function with the user and password variables
            header("Location: register.php");
            //we redirect back to the register.php page
            break;
            

        case 'login':
            $username = $_POST['username'];
            $password = $_POST['password'];
            //$_SESSION['user_priv'] = NULL;
            //We extract username and pasword from the POST method and store them in local variable
            if(login($username,$password) == TRUE){
                setcookie("logged_in", "1", time() + 86400, "/");
                if(user_priv($username) == "admin"){
                    $_SESSION['user_priv'] = "admin";
                    $_SESSION['user'] = $username;
                    log_message("loged in as admin");
                }
                else{
                    $_SESSION['user_priv'] = "user";
                    $_SESSION['user'] = $username;
                    log_message("loged in as user");
                }
                header("location: index.php");
            }
            elseif(login($username,$password) == FALSE){
                $_SESSION['login'] = FALSE;
                log_message("unable to login");
                header("location: index.php");
            }
            else{
                //echo"some sort of problem";
            }
            /*
            We check if the username and coresponding password are in the database if yes we set a cookie (will have to be changed to session variable I think)
            once the user is validated we need to check what kind of rights they have so we call the user_priv method and see if the user priviledge is admin
            if yes then we store that information in session variable called user_priv
            */
            break;

        case 'logout': 
            setcookie("logged_in", "1", time() -1 , "/");
            log_message("loged out");
            unset($_SESSION['user']);
            unset($_SESSION['user_priv']);
            header("location:login.php");
            
            
            break;
        case 'binary_converter':
            $IP_string = $_POST["IP_string"];
            // we extract the IP_string from the post method and call convert_to_binary function with $IP_string variable 
            if(ValidateIP($IP_string) != true){
                $_SESSION['result'] = "IP is NOT correct";
                header("Location: ipvalidation.php");
                break;
            }
            $_SESSION['binary_array'] = convert_to_binary($IP_string);
            log_message("Used binary converter");
            header("Location: ipvalidation.php");
            break;
        case 'slash_notation_converter':
            // we extract the IP_string from the post method and call convert_to_binary function with $IP_string variable 
            $IP_string = $_POST["IP_string"];
            //we validate that the IP_STRING is correct IP format
            if(ValidateIP($IP_string) != true){
                $_SESSION['result'] = "IP is NOT correct";
                header("Location: ipvalidation.php");
                break;
            }
            //we validate that the IP_STRING is a valid subnet mask
            if(validate_subnetmask(convert_to_binary($IP_string)) != true){
                $_SESSION['/mask'] = "incorrect mask";
                header("Location: ipvalidation.php");
                break;
            }
            //we create session variable called /mask where we store the convert to slash notation value which is integer
            $_SESSION['/mask'] = convert_to_slash_notation(convert_to_binary($IP_string));
            log_message("Used slash notation converter");
            header("Location: ipvalidation.php");
            break;
        case 'subnet_mask_converter' :
            //we extract the subnet mask
            $slash_mask = $_POST['slash_subnet_mask'];
            //$_SESSION['curltest'] = "We did it";
            
            /*we want to make sure that only correct subnet mask is accepted. Otherwise we will return error 
            we want to accept people typing just number 24, 16, 32, BUT we also want to accept /24, /16, /32
            Therefore we have to check whether the string has / symbol at the beggining*/

            $slash_array = str_split($slash_mask);
            /*we use str_split which will separate each character of the string into an array of elements
            and we check that the 0 element is / . If yes then we remove it*/
            if($slash_array['0'] == "/" ){
                array_splice($slash_array,0,1);
                $slash_mask = implode("",$slash_array);
                echo"$slash_mask";
            }
            /*If the subnet mask is now in correct format we need to make sure that the subnet mask is now a number in between 0 to 32
            if yes then create a session variable called 'dotted_decimal_mask' which contains a string. This string will be a result of
            the implode function. The implode function takes an array and convert the elements into a string. In this case we also add a delimiter
            that will put a . in between each array element. 
            The array element will be a result of the function convert_to_dotted_decimal
            */
            if($slash_mask >= 0 && $slash_mask <= 32){
                $_SESSION['dotted_decimal_mask'] = implode(".",convert_to_dotted_decimal($slash_mask));
                header("Location: ipvalidation.php");
            }
            //if the subnet mask number is something else than 0 -  32 we return an error which will be displayed in the Javascript error
            else{
                $_SESSION['result'] = "mask is NOT correct";
                header("Location: ipvalidation.php");
            }
            log_message("Used subnet mask converter");
            break;
        case 'download' :
            $file = $_POST['file'];
            $downloads_dir = '/opt/lampp/downloads';
            $file_path = $downloads_dir . "/" . $file;
            if(file_exists($file_path)){
                //Below headers will force the browser to start downloading chosen file
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                // Clear the output buffer
                ob_clean(); // Clear any previous output
                flush(); // Flush the system output buffer
                
                // Read the file and output it to the browser
                readfile($file_path);
                log_message("Downloaded a $file_path");
                break; // Stop further script execution
            
            }
            else{
                echo"File doesn't exist";
            }
            
            break;
        case 'LB_config_parse':
            $LBconfig = $_POST['LBconfig'];
            $LBconfig_filename = $_POST['filename'];
            //echo nl2br(htmlspecialchars($LBconfig));
            $VIP_name = get_VIP_name($LBconfig);
            $VIP_profiles = get_VIP_section($LBconfig,"profiles");
            //echo "VIP name is {$VIP_name}";

            break;
        default:
            echo "Unknown process";
            break;
    }
    exit();
}




function ValidateIP($IP_string){
    
    $IP_array = explode('.', $IP_string);
    $Valid_IP = false;
    foreach($IP_array as $octet){
        if($octet >= 0 && $octet <= 255){
            $Valid_IP = true;
        }
        else {
            $Valid_IP = false;
            break;
        }
    }
    return $Valid_IP;
}
function check_if_private($IP_string){  
    $IP_array = explode('.', $IP_string);
    switch($IP_array[0]){
        case '10' :
            $_SESSION['is_private'] = "This IP is a private IP";
            break;
        case '172' :
            if($IP_array[1] >= 16 && $IP_array[1] <= 31){
                $_SESSION['is_private'] = "This IP is a private IP";
            }
            else{
                $_SESSION['is_private'] = "This IP is a public IP";
            }
            break;
        case  '192':
            if($IP_array[1] == 168){
                $_SESSION['is_private'] = "This IP is a private IP";
            }
            else{
                $_SESSION['is_private'] = "This IP is a public IP";
            }
            break;
        default :
            $_SESSION['is_private'] = "This IP is a public IP";
    }
    return $_SESSION['is_private'];

}
function register_user($user,$password,$user_priv){
    include("database.php");
    //since this is a new user being registered into a DB we will include our database.php file where we are connecting to the db
    mysqli_report(MYSQLI_REPORT_ERROR);
    //this is needed so the mysqli will return FALSE instead of exception (exception is not stored in the variable as FALSE if something is wrong with the object ie is duplicate or something like that)
    if(isset($user_priv)){
        $sql = "INSERT INTO users (user, password,user_priv)
            VALUES ('$user', '$password', '$user_priv')";
    }else{
        $sql = "INSERT INTO users (user, password)
            VALUES ('$user', '$password')";       
    }
    /*We create a string variable that contains the SQL query for inserting a new user into database
    if the user_priv variable is set we will pass it into the query and create an admin user
    otherwise we create the user with default user priviledges
    We will pass it the user and password variable which is coming from the POST method
    */
    $query_verification = mysqli_query($conn,$sql);
    
    /* Here we create a boolean variable. Which stores the result of the query
    */

    mysqli_close($conn);
    return($query_verification);
    /* We close the mysqli connection and return the boolean value of the query
    if the user has been created we return TRUE if not FALSE
    */
}
function login($username,$password){
    include("database.php");
    if($databseconnectivity == false){
        header("location: login.php");
        $_SESSION['DB_check'] = false;
    }
    $sql = "SELECT user , password FROM users WHERE user='$username'";
    $user_verification = mysqli_query($conn, $sql);
    /* 
    We include the database.php script to access our DB connection $conn
    We create and execute SQL query where we select user and password collumns from users DB where user value is matching specified username
    We store the result in $user_verification variable
    */
    $sql_result = mysqli_fetch_assoc($user_verification);
    /*
    We fetch the results into an array
    */
    $db_username = $sql_result['user'];
    $db_password = $sql_result['password'];
    /*
    We create a 2 variables where we store the array user element and password element
    */
    $valid_user = false;
    if($db_username == $username){
        echo"username correct ";
        if($db_password == $password){
            echo"password correct ";
            $valid_user = true;
        }
        else{
            echo"Incorrect password ";
        }
    /*
    We validate if the username and password from the POST method matches the username and password from the DB
    */
    }
    else{
        echo"Incorrect username";
    }
    mysqli_close($conn);
    return($valid_user);
}
function user_priv($username){
    include("database.php");
    $sql = "SELECT user_priv FROM users WHERE user='$username'";
    $user_priv = mysqli_query($conn, $sql);
    $sql_result = mysqli_fetch_assoc($user_priv);
    $db_user_priv = $sql_result['user_priv'];
    return($db_user_priv);    
}
function convert_to_binary($IP_string){
    $IP_array = explode('.', $IP_string);
    //we separate the string into array of 4 octets
    $bit_array = array("128", "64", "32", "16", "8", "4", "2", "1");
    //we declare our binary bits which we use to compare and substract from our octets
    $binary_array = NULL;
    //we create an array for our binary numbers. We keep it empty
    $index = 0;
    // this index variable is used to write the binary bits into correct element position of the $binary_array array
    foreach($IP_array as $octet){
        //foreach loop where we take each octet in the IP_array and perform some action on it
        foreach($bit_array as $bit){
            /* 
            This is the main loop. We go through the $bit_array and compare each element to the $octet variable. If the octet has higher value we know
            that the bit will be 1. We then substract the that $bit value from the $octet and continue the process until the octet value is 0
            We store the bit value (either 0 or 1) in the $bit_array at $index element position.
            the $index is increased by 1 each loop cycle
            */
            if($octet >= $bit){
                $binary_array["$index"] = 1;
                $octet = $octet - $bit;
                $index = $index + 1;
                
            }
            else{
                $binary_array["$index"] = 0;
                $index = $index + 1;
            }  
             
            
        }
        //we can display it as a array of integers with dots in between octets if we want by removing the // from the below rows and the array_pop function below it
        //$binary_array["$index"] = ".";   
        //$index = $index + 1; 

    }
    //array_pop($binary_array);
    return($binary_array);
}
function validate_subnetmask($binary_array){
    /*
    In this function we take the binary array and loop through each element. If the element is 1 than we continue.
    If the element is 0 we start another loop where we loop through the rest of the binary array and make sure that all the other elements are 0
    if yes than we know that the binary array is valid subnet mask and return TRUE. Otherwise we return FALSE
    */
    $array_position = 0;
    $number_of_array_elements = count($binary_array);
    $valid_mask = false;
    foreach($binary_array as $x){
        if($x == 1){
            $array_position = $array_position + 1;
        }
        else{
            //$array_elements_left = $number_of_array_elements - $array_position;
            for($array_position; $array_position <= $number_of_array_elements; $array_position++){
                if($binary_array[$array_position] == 1){
                    $valid_mask = false;
                    break;
                }
                else{
                    $valid_mask = true;
                }
            }
            break;
        }
            
    }
    return($valid_mask);

}
function convert_to_slash_notation($binary_array){
    /*
    Here we loop through the binary array, count the ones and return integer value
    */
    $bits = 0;
    foreach($binary_array as $x){
        if($x == 1){
            $bits = $bits + 1;
        }
        else{
            break;
        }
    }
    return($bits);
}
function convert_to_dotted_decimal($slash_mask){
    //In this function we declare octets 1 - 4 
    $octet1 = 0;
    $octet2 = 0;
    $octet3 = 0;
    $octet4 = 0;
    //We also divide the slash mask by 8 as each octet has 8 bits and calculate how many bits we have leftover
    $bits_left = $slash_mask - ((floor($slash_mask / 8)) * 8);
    //we declare the bits_array that will be used for subnet mask calculation
    $bit_array = array("128", "64", "32", "16", "8", "4", "2", "1");
    /*here we calculate how many octets will actually be 255 . As we know each octet has 8 bits
    so when we divide the slash_mask by 8 and round it to lower we will know hom many of the octets will have 8 bits and therefore will be 255
    */
    switch(floor($slash_mask / 8)){
        case '0' :
            /*This for loop is for converting the leftover bits. We will loop through the bit_array and add the element from each loop
            $bits is the number of element position, we loop until we reach the same number as the bits_left and in each itireation we 
            add the element to our octet
            so if our bits_left is 4 we will add 128 + 64 +32 + 16 which results in 240
            */
            for($bits = 0; $bits < $bits_left; $bits++){
                $octet1 = $octet1 + $bit_array[$bits];
            }
            break;
        case '1' :
            $octet1 = 255;
            for($bits = 0; $bits < $bits_left; $bits++){
                $octet2 = $octet2 + $bit_array[$bits];
            }
            break;
        case '2' :
            $octet1 = 255;
            $octet2 = 255;
            for($bits = 0; $bits < $bits_left; $bits++){
                $octet3 = $octet3 + $bit_array[$bits];
            }
            break;
        case '3' :
            $octet1 = 255;
            $octet2= 255;
            $octet3 = 255;
            for($bits = 0; $bits < $bits_left; $bits++){
                $octet4 = $octet4 + $bit_array[$bits];
            }
            break;
        case '4' :
            $octet1 = 255;
            $octet2 = 255;
            $octet3 = 255;
            $octet4 = 255;
            break;
    }
    $dotted_decimal_mask_array = array($octet1,$octet2,$octet3,$octet4);
    return($dotted_decimal_mask_array);
}
function log_message($string_message){
    $file_location = '/opt/lampp/var/log/errorlogtest.log';
    $log_message = date("Y/m/d") . " " . date("H:i:s") . " | User: " . $_SESSION['user'] . " | Message: " . $string_message;
    file_put_contents($file_location, "$log_message" . PHP_EOL, FILE_APPEND);
}
function get_VIP_name($LBconfig){
    $LBconfig_array = preg_split('/\r\n|\r|\n/', $LBconfig);
    //This preg_split function will split the string by regex for "new line"
    //and will keep the config as an array of lines
    $LBconfig_firstline = $LBconfig_array["0"];
    //we can then access the first line which contains the VIP name and extract it
    $slash_element = strrpos($LBconfig_firstline, "/");
    //We locate the last / element in the string (we know that one is just before the VIP name)
    $VIPname = substr($LBconfig_firstline, ($slash_element +1));
    //We will remove everything that is before the slash element (and +1 to remove the element as well)
    $VIPname = substr($VIPname, 0, -2);
    //We remove last 2 characters because we know that those are always empty space and { in our config file.
    return $VIPname;
}
function get_VIP_section($LBconfig, $section){
    $LBconfig_array = preg_split('/\r\n|\r|\n/', $LBconfig);
    $x=0;
    $offset = 0;
    $lenght = 0;
    //echo $section;
    foreach ($LBconfig_array as $LBconfig_line){
        //echo "$LBconfig_line <br>";
        if (strpos($LBconfig_line, $section) !== False){
            
            $offset = $x;
            $x ++;
            $bracket_count = 1;
            //echo"we have found the section start at line $x <br>";
            
            while ($bracket_count > 0 && $x < count($LBconfig_array)){
                $LBconfig_line = $LBconfig_array[$x]; // Get the current line
                //echo "$LBconfig_line <br>"; // Output the current line

                // Count opening and closing brackets
                $bracket_count += substr_count($LBconfig_line, '{'); // Add the count of '{'
                $bracket_count -= substr_count($LBconfig_line, '}'); // Subtract the count of '}'

                // Move to the next line
                $x++;
            }
            $lenght = $x;
            //echo"we have found the section end at line $x <br>";

        }
        else{
            $x ++;
        }
    }
    $LBconfig_section_array = array_splice($LBconfig_array, $offset, ($lenght - $offset));
    foreach($LBconfig_section_array as $line){
        echo "$line <br>";
    }

}
?>
