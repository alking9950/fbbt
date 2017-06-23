<?php
require __DIR__.'/loader.php';

define("data", __DIR__."/data");
define("fb_data", data."/fb_data");

is_dir(data) or mkdir(data);
is_dir(fb_data) or mkdir(fb_data);
(is_dir(data) and is_dir(fb_data)) or die("Gagal membuat directory !");

$a = json_decode(file_get_contents("/root/botfb/config/ammarfaizi2_token.txt"), 1);
header("Content-type:text/plain");
$ch = curl_init("https://graph.facebook.com/me/feed?limit=1&fields=id&access_token={$a[1]}");
curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false
	));
$out = curl_exec($ch);
curl_close($ch);
$out = json_decode($out, 1);
$curpos  = substr($out['data'][0]['id'], strpos($out['data'][0]['id'], "_")+1);
if (file_exists(__DIR__."/current.txt")) {
	$old = file_get_contents(__DIR__."/current.txt");
} else {
	$old = "";
}

if ($old!=$curpos) {
	file_put_contents(__DIR__."/current.txt", $curpos);
	$cronlist = array();
	if (is_dir(fb_data."/cookies")) {
		$scan = scandir(fb_data."/cookies");
		unset($scan[0], $scan[1]);
		foreach ($scan as $val) {
			$b = implode(explode("_", $val, -1));
			if ($b!=="global") {
				PHPFBHandler::init($b);
				$sel = array("LIKE", "LOVE", "WOW");
				PHPFBHandler::getInstance()->reaction($curpos, $sel[rand(0,2)]);
				print $b ." ".$curpos."\n\n";
			}
		}
	}
} else {
	print "old_post";
}
