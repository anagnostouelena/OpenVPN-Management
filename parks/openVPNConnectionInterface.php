<?php
 include 'parkRepository.php';
    class ClientManipulation {
        public $db;

        public function __construct() {
        }

        public function killClient($targetClient) {
            $openvpnHost = '127.0.0.1'; 
            $openvpnPort = 7575;
            $socket = fsockopen($openvpnHost, $openvpnPort, $errno, $errstr, 10);
            
            if ($socket) {
                $command = "kill $targetClient 0" . "\n";
                fwrite($socket, $command);
                $response = fread($socket, 8192);
        
                fclose($socket);
            } else {
                // Handle connection error
                echo "Error: $errstr ($errno)\n";
            }

            $parksRepo = new ParksRepository();
            $parksRepo->setClientStatus(0, $targetClient);
            $logFilePath = "/etc/openvpn/server/openvpn-status.log";
            while(true) {
                $logContents = file_get_contents($logFilePath);
                $targetPosition = strpos($logContents, $targetClient);
                if ($targetPosition !== false) {}
                else {
                    break;
                }
                sleep(0.5);
            }

        }

        public function connectClient($targetClient) {

            $parksRepo = new ParksRepository();
            $parksRepo->setClientStatus(1, $targetClient);
            $logFilePath = "/etc/openvpn/server/openvpn-status.log";
            while(true) {
                $logContents = file_get_contents($logFilePath);
                $targetPosition = strpos($logContents, $targetClient);
                if ($targetPosition !== false) {
                    break;
                }
                sleep(0.5);
            }

        }
    }


?>
