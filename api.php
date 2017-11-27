<?php

include "./db.php";

class API {    

    public function __construct(){
            $this->inputs();
    }

    private function inputs(){
        //header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://select
                $this->getContentQueue();
                break;     
            case 'POST'://insert
                $this->postInAQueue();
                break;                
            default://no more method allow
                echo 'method now allow';
                break;
        }
    }

    
    private function getContentQueue(){

        $url = $_GET['url'];
        $urlArray = array();
        $urlArray = explode("/",$url);

        if ($urlArray[0]=="queue" && count($urlArray)<=2){
            $db = new DB();

            if (count($urlArray)==1){
                $response = $db->getQueues();
            }
            else{
                $filter = $urlArray[1];
                $response = $db->getQueuesFiltering($filter);
            }

            if (count($response)==0){
                $this->response("Error", "There was a problem in the request by GET");
            } 
            else{
                $this->response("Ok", $response);
            }
        }
        else{
            $this->response("Error", "There was a problem in the request by GET");
            }
    }     

    private function postInAQueue(){


        $url = $_GET['url'];
        $urlArray = array();
        $urlArray = explode("/",$url);

        if ($urlArray[0]=="queue" && (count($urlArray)>2 || count($urlArray)<7)){
            //$db = new DB();

            array_shift($urlArray);
            $type_person = $urlArray[0];
            array_shift($urlArray);

            if ($type_person == "Citizen" || $type_person == "Anonymous"){
                
                $name = $urlArray[0];
                array_shift($urlArray);
                $surname = $urlArray[0];
                array_shift($urlArray);

                //if ($name!="" && $surname!="" && $type_person=="Citizen"){
                if ($name=="" || $surname=="" && $type_person=="Citizen"){
                   $this->response("Error", "There was a problem in the request by POST. Name and surname obligated for a citizen");
                }
                else{
                    if (count($urlArray)>1){
                        $organisation = $urlArray[0];
                        array_shift($urlArray);
                    }

                    $service = $urlArray[0];

                    if ($service=="Council Tax" || $service=="Benefits" || $service=="Rent"){
                        $response = $db->insert($type,$name,$surname,$org,$service);
                        $this->response("Ok", $response);
                    }
                    else{
                        $this->response("Error", "There was a problem in the request by POST. Service not permitted");
                    }
                }
            }
            else{
                $this->response("Error", "There was a problem in the request by POST. Type of user not allow");
            }
        }
        else{
            $this->response("Error", "There was a problem in the request by POST. Wrong format");
            }
    } 


    private function response($status="", $message="") {
        $response = array("status" => $status ,"message"=>$message);  
        echo json_encode($response,JSON_PRETTY_PRINT);  
    } 
}