<?php
    $directory = '/etc/openvpn/server/cdd'; // replace this with the actual path to your directory
    $host = 'localhost';
    $user = 'parks';
    $password = 'Hag1234';
    $database = 'parks_management';

    $connection = new mysqli($host, $user, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $table_name = 'parks';
    $columns_to_read = ['park_name', 'common_name'];

    $columns_str = implode(', ', $columns_to_read);
    $query = "SELECT $columns_str FROM $table_name";

    $result = $connection->query($query);

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    $files = scandir($directory);
    $files = array_diff($files, array('.', '..'));

    $data = array();

foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        $contents = file_get_contents($directory . '/' . $file);

        preg_match('/ifconfig-push\s+([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/', $contents, $matches);
        $ip = isset($matches[1]) ? $matches[1] : null;

        // Find matching data in $data based on file name
        $matchingData = array_filter($data, function ($entry) use ($file) {
            return pathinfo($entry['common_name'], PATHINFO_FILENAME) === pathinfo($file, PATHINFO_FILENAME);
        });

        if (!empty($matchingData)) {
            // If matching data found, update the entry
            $matchingEntry = reset($matchingData);
            $matchingEntry['entries'][] = array(
                'file_name' => $file,
                'ip' => $ip,
            );
        } else {
            // Fetch 'park_name' from the database based on 'common_name'
            $query = "SELECT park_name FROM parks WHERE common_name = '" . pathinfo($file, PATHINFO_FILENAME) . "'";
            $result = $connection->query($query);

            if ($result && $row = $result->fetch_assoc()) {
                $park_name = $row['park_name'];
            } else {
                $park_name = null;
            }

            // If no matching data found, create a new entry using file name and fetched 'park_name'
            $data[] = array(
                'common_name' => pathinfo($file, PATHINFO_FILENAME), // use file name as common_name
                'park_name' => $park_name,
                'ip' => $ip
            );
        }
    }
}

// Convert the data array to JSON format
$json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Specify the path and filename for the JSON file
$json_file_path = '/var/www/html/parks/output.json';

// Write the JSON data to the file
file_put_contents($json_file_path, $json_data);

echo "Data has been written to $json_file_path";

    
?>
