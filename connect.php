
<?php

require 'header.php';


$controllerIP   = '172.31.176.1';
$controllerPort = '8043';
$controllerID   = 'aef48779df8499ffe3f5cb973c945df4';

$username = 'myaloha';
$password = 'MyAloha2025@';

$expireMicroSeconds = 3600 * 1000000; // 1 giờ

// session_start();

$clientMac = $_SESSION["clientMac"];
$apMac     = $_SESSION["apMac"];
$ssidName  = $_SESSION["ssidName"];
$radioId   = $_SESSION["radioId"];
$site      = $_SESSION["site"];
$redirect  = $_SESSION["redirectUrl"] ?? 'https://portal.myportal.vn/app/followOA/show';


$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://$controllerIP:$controllerPort/$controllerID/api/v2/hotspot/login",
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_COOKIEJAR => __DIR__.'/omada.cookie',
  CURLOPT_COOKIEFILE => __DIR__.'/omada.cookie',
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
  CURLOPT_POSTFIELDS => json_encode([
    'name' => $username,
    'password' => $password
  ])
]);

$response = curl_exec($curl);
$data = json_decode($response, true);
$csrfToken = $data['result']['token'];


$authData = [
  'clientMac' => $clientMac,
  'apMac'     => $apMac,
  'ssidName'  => $ssidName,
  'radioId'   => $radioId,
  'site'      => $site,
  'authType'  => 4,
  'time'      => $expireMicroSeconds
];

curl_setopt_array($curl, [
  CURLOPT_URL => "https://$controllerIP:$controllerPort/$controllerID/api/v2/hotspot/extPortal/auth",
  CURLOPT_POSTFIELDS => json_encode($authData),
  CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    'Csrf-Token: ' . $csrfToken
  ]
]);

$res = curl_exec($curl);
curl_close($curl);

$result = json_decode($res, true);

if ($result['errorCode'] != 0) {
  die('Authorize failed');
}

header("Location: $redirect");
exit;