<?php
require 'header.php';

// session_start();

$_SESSION["clientMac"] = $_GET["clientMac"] ?? '';
$_SESSION["apMac"]     = $_GET["apMac"] ?? '';
$_SESSION["ssidName"]  = $_GET["ssidName"] ?? '';
$_SESSION["radioId"]   = $_GET["radioId"] ?? '';
$_SESSION["site"]      = $_GET["site"] ?? '';
$_SESSION["t"]         = $_GET["t"] ?? '';
$_SESSION["redirectUrl"] = $_GET["redirectUrl"] ?? 'https://www.google.com';
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>WiFi Access</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
  <div style="text-align:center;margin-top:80px;">
    <h2>Chào mừng bạn</h2>
    <form method="post" action="connect.php">
      <button type="submit" style="padding:12px 32px;font-size:18px;">
        Kết nối Wi-Fi
      </button>
    </form>
  </div>
</body>
</html>