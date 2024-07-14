<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/mainLib.php";
$gs = new mainLib();
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/commands.php";
require_once "../../config/misc.php";

$userName = !empty($_POST['userName']) ? ExploitPatch::charclean($_POST['userName']) : "";
$gameVersion = !empty($_POST['gameVersion']) ? ExploitPatch::number($_POST['gameVersion']) : 0;
$comment = ExploitPatch::rucharclean($_POST["comment"]);
$commentLength = ($gameVersion >= 20) ? mb_strlen(ExploitPatch::url_base64_decode($comment)) : mb_strlen($comment);
if($enableCommentLengthLimiter && $commentLength > $maxCommentLength) exit("temp_0_You cannot post comments above $maxCommentLength characters! (Your's ".$commentLength.")");
$comment = ($gameVersion < 20) ? ExploitPatch::url_base64_encode($comment) : $comment;
$levelID = ($_POST['levelID'] < 0 ? '-' : '').ExploitPatch::number($_POST["levelID"]);

if(strpos($levelID, '-') === 0) {
    $checkLevelExist = $db->prepare("SELECT * FROM lists WHERE listID = :levelID");
	$checkLevelExist->execute([':levelID' => ltrim($levelID, '-')]);
} else {
    $checkLevelExist = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID");
	$checkLevelExist->execute([':levelID' => $levelID]);
}
if($checkLevelExist->rowCount() == 0) die("-1");

$id = $gs->getIDFromPost();
$userID = $gs->getUserID($id, $userName);
$uploadDate = time();
$decodecomment = ExploitPatch::url_base64_decode($comment);
$command = Commands::doCommands($id, $decodecomment, $levelID);
if($command) ($_POST['gameVersion'] > 20 ? exit("temp_0_".$command) : exit('-1'));
$checkCommentBan = $gs->getPersonBan($id, $userID, 3);
if($checkCommentBan) ($_POST['gameVersion'] > 20 ? exit("temp_".($checkCommentBan['expires'] - time())."_".ExploitPatch::rutoen(ExploitPatch::url_base64_decode($checkCommentBan['reason']))) : exit('-10'));
if($id != "" AND $comment != "") {
	$query = $db->prepare("INSERT INTO comments (userName, comment, levelID, userID, timeStamp, percent) VALUES (:userName, :comment, :levelID, :userID, :uploadDate, :percent)");
	$query->execute([':userName' => $userName, ':comment' => $comment, ':levelID' => $levelID, ':userID' => $userID, ':uploadDate' => $uploadDate, ':percent' => $percent]);
	echo 1;
}else{
	echo -1;
}
?>