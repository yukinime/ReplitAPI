<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$validCommands = array("Matikan Lampu", "Nyalakan Lampu Merah", "Nyalakan Lampu Hijau", "Nyalakan Lampu Biru");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (file_exists("data.csv")) {
    $file = fopen("data.csv", "r");
    $data = [];
    while (($line = fgetcsv($file)) !== FALSE) {
      $data[] = array("command" => $line[0]);
    }
    fclose($file);
    echo json_encode(array("status" => "success", "data" => $data));
  } else {
    echo json_encode(array("status" => "success", "data" => []));
  }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true);
  if (isset($input['command']) && in_array($input['command'], $validCommands)) {
    $file = fopen("data.csv", "w");
    fputcsv($file, array($input['command']));
    fclose($file);
    echo json_encode(array("status" => "success", "data" => array("command" => $input['command'])));
  } else {
    http_response_code(400);
    echo json_encode(array("message" => "Bad Request: Invalid Command"));
  }
}

elseif ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  // Handle Preflight OPTIONS request
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");
  header("Content-Type: application/json; charset=UTF-8");
  exit;
}

else {
  http_response_code(405);
  echo json_encode(array("message" => "Method Not Allowed"));
}
?>
