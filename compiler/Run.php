<?php
$input = json_decode(file_get_contents("php://input"), true);

$source_code = $input["code"] ?? "";
$stdin = $input["input"] ?? "";
$language_id = (int)($input["language"] ?? 71);

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=false&wait=true",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    "source_code" => $source_code,
    "language_id" => $language_id,
    "stdin" => $stdin
  ]),
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "X-RapidAPI-Host: judge0-ce.p.rapidapi.com",
    "X-RapidAPI-Key: " . "3e760cbb43msh4875d757ec9df86p11f9b0jsn7d8ba5230cf0" // Replace this with your actual API key
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo json_encode(["status" => "error", "output" => $err]);
    exit;
}

$data = json_decode($response, true);

$output = $data['stdout'] ?? $data['stderr'] ?? $data['compile_output'] ?? 'No output';
$status = $data['status']['description'] ?? 'Unknown';
$time = $data['time'] ?? '0';
$memory = $data['memory'] ?? '0';

echo json_encode([
  "status" => $status,
  "output" => $output,
  "time" => $time,
  "memory" => $memory
]);
