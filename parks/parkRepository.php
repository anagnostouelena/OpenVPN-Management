<?php
    class ParksRepository {
        public $db; 

        public function __construct() {
            try {
                $this->db = new PDO("mysql:host=$db_host;dbname=$db_name", "$db_user", "$db_pass");
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        public function getParks() {
            try {
                //getting parks data
                $queryParks = "SELECT * FROM parks;";
                $stmtParks = $this->db->query($queryParks);
                $resultParks = $stmtParks->fetchAll(PDO::FETCH_ASSOC);

                for ($i = 0; $i < count($resultParks); $i++) { 
                    $parkID = $resultParks[$i]['park_id'];
                    $queryLinks = "SELECT * FROM links WHERE park_id = $parkID;";
                    $stmtLinks = $this->db->query($queryLinks);
                    $resultLinks = $stmtLinks->fetchAll(PDO::FETCH_ASSOC);

                    $ilektronomosLink = '';
                    $camerasLink = '';
                    $loggerLink = '';
                    $cn = $resultParks[$i]['common_name'];
                    $cnFound = 0;
                    $logFilePath = "/etc/openvpn/server/openvpn-status.log";

                    $logContents = file_get_contents($logFilePath);
                    $targetPosition = strpos($logContents, $cn);
                    if ($targetPosition !== false) {
                        $cnFound = 1;
                    }
                    else {
                        $cnFound = 0;
                    }
                
                    foreach ($resultLinks as $link) {
                        switch ($link['link_name']) {
                            case 'ilektronomos':
                                $ilektronomosLink = $link['link'];
                                break;
                            case 'kameres':
                                $camerasLink = $link['link'];
                                break;
                            case 'logger':
                                $loggerLink = $link['link'];
                                break;
                        }
                    }   

                    $data['parks'][] = array(
                        'parkName' => $resultParks[$i]['park_name'],
                        'parkLocation' => $resultParks[$i]['park_location'],
                        'ilektronomosLink' => $ilektronomosLink,
                        'camerasLink' => $camerasLink,
                        'loggerLink' => $loggerLink,
                        'cn' => $resultParks[$i]['common_name'],
                        'on'=> $cnFound
                    );

       
                }
                
                $reducedJson = json_encode($data['parks']);
                return $reducedJson;

            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }   
        }

        public function savePark($datajson){
            try {                
                $commonName = generateRandomString();
                $parkName = $datajson['parkName'];
                $parkLocation = $datajson['parkLocation'];
                $ilektronomosLink = $datajson['ilektronomosLink'];
	                $camerasLink = $datajson['camerasLink'];
                $loggerLink = $datajson['loggerLink'];
               
                
                // save a new park
                $sql = "INSERT INTO parks (park_name, park_location, common_name,client_status) 
                VALUES ('$parkName', '$parkLocation', '$commonName',0);";
                $this->db->exec($sql);
    
                //getting the last park id
                $lastParkId = "SELECT MAX(park_id) AS last_park_id FROM parks;";
                $stmt = $this->db->query($lastParkId);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastId = $result['last_park_id'];


                // save the links for the park
                $sql = "INSERT INTO links (link_name, link, park_id) 
                VALUES ('ilektronomos', '$ilektronomosLink', '$lastId');";
                $this->db->exec($sql);

                $sql = "INSERT INTO links (link_name, link, park_id) 
                VALUES ('kameres', '$camerasLink', '$lastId');";
                $this->db->exec($sql);

                $sql = "INSERT INTO links (link_name, link, park_id) 
                VALUES ('logger', '$loggerLink', '$lastId');";
                $this->db->exec($sql);

              
            }catch(PDOException $e) {
              echo $sql . "<br>" . $e->getMessage();
            }

        }

        public function setClientStatus($clientStatus, $cn) {
            $sql = "UPDATE parks SET client_status='$clientStatus' WHERE common_name='$cn';";
            $this->db->exec($sql);
        }





    }



    function generateRandomString() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }


?>
