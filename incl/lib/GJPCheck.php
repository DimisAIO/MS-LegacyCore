<?php
require_once dirname(__FILE__)."/XORCipher.php";
require_once dirname(__FILE__)."/generatePass.php";
include_once dirname(__FILE__)."/mainLib.php";

class GJPCheck {
	public static function check($gjp, $accountID) {
		include dirname(__FILE__)."/connection.php";
		include dirname(__FILE__)."/exploitPatch.php";
		include dirname(__FILE__)."/../../config/security.php";
		$ml = new mainLib();
		if($sessionGrants){
			$ip = $ml->getIP();
			$query = $db->prepare("SELECT count(*) FROM actions WHERE type = 16 AND value = :accountID AND value2 = :ip AND timestamp > :timestamp");
			$query->execute([':accountID' => $accountID, ':ip' => $ip, ':timestamp' => time() - 3600]);
			if($query->fetchColumn() > 0){
				return 1;
			}
		}
		$gjpdecode = str_replace("_","/",$gjp);
		$gjpdecode = str_replace("-","+",$gjpdecode);
		$gjpdecode = ExploitPatch::url_base64_decode($gjpdecode);
		$gjpdecode = XORCipher::cipher($gjpdecode,37526);
		$validationResult = GeneratePass::isValid($accountID, $gjpdecode);
		if($validationResult == 1 AND $sessionGrants){
			$ip = $ml->getIP();
			$query = $db->prepare("INSERT INTO actions (type, value, value2, timestamp) VALUES (16, :accountID, :ip, :timestamp)");
			$query->execute([':accountID' => $accountID, ':ip' => $ip, ':timestamp' => time()]);
		}
		return $validationResult;
	}

	public static function validateGJPOrDie($gjp, $accountID){
		if(self::check($gjp, $accountID) != 1)
			exit("-1");
	}

	public static function validateGJP2OrDie($gjp2, $accountID){
		if(GeneratePass::isGJP2Valid($accountID, $gjp2) != 1)
			exit("-1");
	}

	/**
	 * Gets accountID from the UDID in POST parameters.
	 *
	 * @return     The account id
	 */
	public static function getAccountIDOrDie(){
		require_once "../lib/exploitPatch.php";
		
		$gs = new mainLib();
		$udid = $gs->getUDID();
		$userInfo = $db->prepare("SELECT userID FROM users WHERE extID = :udid");
		$userInfo->execute([':udid' => $udid]);
		if($userInfo->rowCount() == 0) {
			exit("-1");
		}

		$row = $userInfo->fetch(PDO::FETCH_ASSOC);
		return $row['userID'];
	}
}
?>
