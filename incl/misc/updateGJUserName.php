<?php
// Is this even used in life? :(
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/GJPCheck.php";

$userName = ExploitPatch::charclean($_POST["userName"]);
$userID = GJPCheck::getAccountIDOrDie();

$query = $db->prepare("UPDATE users SET userName=:userName WHERE userID=:userID");
$query->execute([':userName' => $userName, ':userID' => $userID]);

exit("1");