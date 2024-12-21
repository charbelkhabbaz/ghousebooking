<?php

// Database connection settings
$hname = 'localhost'; // Hostname for the database
$uname = 'root'; // Username for the database
$pass = ''; // Password for the database
$db = 'ghousebooking'; // Database name
$port='3307'; // Port for MySQL connection

// Establish connection to the MySQL database
$con = mysqli_connect($hname, $uname, $pass, $db, $port);

// Check if the connection was successful
if (!$con) {
  die("Cannot Connect to Database" . mysqli_connect_error()); // If connection fails, output error
}

// Function to sanitize input data
function filteration($data)
{
  foreach ($data as $key => $value) {
    // Remove unnecessary spaces, escape special characters, strip tags, and convert to HTML entities
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    $data[$key] = $value; // Return sanitized data
  }
  return $data;
}

// Function to select all records from a table
function selectAll($table)
{
  $con = $GLOBALS['con']; // Use global connection variable
  // Execute the SQL query to get all records from the specified table
  $res = mysqli_query($con, "SELECT * FROM $table");
  return $res; // Return the result of the query
}

// Function to execute a select query with parameters
function select($sql, $values, $datatypes)
{
  $con = $GLOBALS['con']; // Use global connection variable
  // Prepare the SQL query
  if ($stmt = mysqli_prepare($con, $sql)) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Get the result set of the query
      $res = mysqli_stmt_get_result($stmt);
      mysqli_stmt_close($stmt); // Close the statement
      return $res; // Return the result
    } else {
      mysqli_stmt_close($stmt); // Close the statement in case of failure
      die("Query cannot be executed - Select"); // Output error if query execution fails
    }
  } else {
    die("Query cannot be prepared - Select"); // Output error if query preparation fails
  }
}

// Function to execute an update query with parameters
function update($sql, $values, $datatypes)
{
  $con = $GLOBALS['con']; // Use global connection variable
  // Prepare the SQL query
  if ($stmt = mysqli_prepare($con, $sql)) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Get the number of affected rows
      $res = mysqli_stmt_affected_rows($stmt);
      mysqli_stmt_close($stmt); // Close the statement
      return $res; // Return the result
    } else {
      mysqli_stmt_close($stmt); // Close the statement in case of failure
      die("Query cannot be executed - Update"); // Output error if query execution fails
    }
  } else {
    die("Query cannot be prepared - Update"); // Output error if query preparation fails
  }
}

// Function to execute an insert query with parameters
function insert($sql, $values, $datatypes)
{
  $con = $GLOBALS['con']; // Use global connection variable
  // Prepare the SQL query
  if ($stmt = mysqli_prepare($con, $sql)) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Get the number of affected rows
      $res = mysqli_stmt_affected_rows($stmt);
      mysqli_stmt_close($stmt); // Close the statement
      return $res; // Return the result
    } else {
      mysqli_stmt_close($stmt); // Close the statement in case of failure
      die("Query cannot be executed - Insert"); // Output error if query execution fails
    }
  } else {
    die("Query cannot be prepared - Insert"); // Output error if query preparation fails
  }
}

// Function to execute a delete query with parameters
function delete($sql, $values, $datatypes)
{
  $con = $GLOBALS['con']; // Use global connection variable
  // Prepare the SQL query
  if ($stmt = mysqli_prepare($con, $sql)) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Get the number of affected rows
      $res = mysqli_stmt_affected_rows($stmt);
      mysqli_stmt_close($stmt); // Close the statement
      return $res; // Return the result
    } else {
      mysqli_stmt_close($stmt); // Close the statement in case of failure
      die("Query cannot be executed - Delete"); // Output error if query execution fails
    }
  } else {
    die("Query cannot be prepared - Delete"); // Output error if query preparation fails
  }
}
?>
