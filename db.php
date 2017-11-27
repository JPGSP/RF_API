<?php

class DB {
    
    protected $mysqli;
    const LOCALHOST = '127.0.0.1';
    const USER = 'root';
    const PASSWORD = 'password';
    const DATABASE = 'firmsteps';
    
    public function __construct() {   
        try{//Create connection to the database
            $this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
        }
        catch (mysqli_sql_exception $e){ //In case there is an error ...
            http_response_code(500);
            exit;
        }     
    } 
    
    public function getQueues(){     
        $result = $this->mysqli->query('SELECT * FROM queue');          
        $all_records = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $all_records; 
    }

    public function getQueuesFiltering($parameter){    
        $stmt = $this->mysqli->prepare("SELECT * FROM queue WHERE type=? ; ");
        $stmt->bind_param('s', $parameter);
        $stmt->execute();
        $result = $stmt->get_result();        
        $records_filtered = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $records_filtered;              
    }
    
    public function insert($type,$name,$surname,$org,$service){
        $stmt = $this->mysqli->prepare("INSERT INTO people(firstname, lastname, organisation, type, service) VALUES (?,?,?,?,?); ");
        $stmt->bind_param('s', $name);
        $stmt->bind_param('s', $surname);
        $stmt->bind_param('s', $organisation);
        $stmt->bind_param('s', $type);
        $stmt->bind_param('s', $service);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;
    }
}