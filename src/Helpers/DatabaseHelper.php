<?php 
namespace Ngfw\LaravelStack\Helpers;

class DatabaseHelper
{
    protected $host;
    protected $username;
    protected $password;

    public function __construct($host, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    public function createDatabase($dbName)
    {
        $mysqli = new \mysqli($this->host, $this->username, $this->password);

        if ($mysqli->connect_error) {
            return false;
        }

        $result = $mysqli->query("CREATE DATABASE IF NOT EXISTS `$dbName`");
        $mysqli->close();

        return $result;
    }

    public function databaseExists($dbName)
    {
        $mysqli = new \mysqli($this->host, $this->username, $this->password);

        if ($mysqli->connect_error) {
            return false;
        }

        // Query to check if the database exists
        $result = $mysqli->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'");

        // Close connection
        $mysqli->close();

        // Return true if the database exists, false otherwise
        return $result && $result->num_rows > 0;
    }
}
