<?php

    function mysql_field_array( $query ) {  
        $field = mysql_num_fields( $query );  
        for ( $i = 0; $i < $field; $i++ ) {       
            $names[] = mysql_field_name( $query, $i );       
        }       
        return $names;    
    }

    define('DB_NAME', 'LogTE');
    /** MySQL database username */
    define('DB_USER', 'root');
    /** MySQL database password */
    define('DB_PASSWORD', '');
    /** MySQL hostname */
    define('DB_HOST', 'localhost');

    // mysql database connection details
    $host = DB_HOST;
    $username = DB_USER;
    $password = DB_PASSWORD;
    $dbname = DB_NAME;
    $tblname = "studies";


    // output headers so that the file is downloaded rather than displayed
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $tblname . '.csv');
    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // open connection to mysql database
    $connection = mysqli_connect($host, $username, $password, $dbname) or die("Connection Error " . mysqli_error($connection));
    
    // fetch mysql table rows
    $sql = "select * from " . $tblname;
    $result = mysqli_query($connection, $sql) or die("Selection Error " . mysqli_error($connection));

    $row = mysqli_fetch_assoc($result);
    $fields = array_keys($row);

    fputcsv($output, $fields);
    fputcsv($output, $row);

    while($row = mysqli_fetch_assoc($result))
    {
        fputcsv($output, $row);
    }

    //close the db connection
    mysqli_close($connection);
?>