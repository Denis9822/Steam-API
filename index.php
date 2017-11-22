<?php

@ob_start();
session_start();
?>

<style>
	*{

		padding: 0;
		margin: 0;	
	}

	.wrapp
	{

		border: 1px solid silver;

		width: 50vw;
		margin: 15vh auto;

	}
	.row
	{
		margin: 0 auto;	
		height: 15vh;	
		border: 1px solid silver;
		padding: 1vw;
	}

	.content
	{
		height: 100%;
		width: 10vw;
		background-color: #333;
		float: left;
		margin-left: 1.5vw;

	}

	img{

		height: auto;
		width: 100%;
		margin: 0,auto;
		border: 2px solid white;
	}

	.name
	{
		color: white;

		font-family: 'Tahoma';

		font-style: 15px;
		font-weight: bold;

		text-align: center;
		

	}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>

	<?php



	
require 'openid.php';


$_STEAMAPI = "AB496973E73A221D8B9B81C43E4DC6E7";

try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID('steam.ru');

    if(!$openid->mode) {
        if(isset($_GET['login'])) {
      		 $openid->identity = 'http://steamcommunity.com/openid';
        
        	 header("Location: {$openid->authUrl()}");
        
      
        }
        else
        {
        	echo "<h2> Connect to Steam </h2>";

        	echo "<form action='?login' method='post'>";

        	echo " <input type='image' src = 'https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png' /> ";
        	echo "<form>";
        }

    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
    	if ($openid->validate())
    	{
    		$id = $openid->identity;
    		$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
    		preg_match($ptn,$id,$matches);

    		$url =  "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=$matches[1]";
    		$invent = "http://steamcommunity.com/profiles/$matches[1]/inventory/json/570/2";

    		$json_object = file_get_contents($url);
    		$json_decoded = json_decode($json_object);

    		$json_object2 = file_get_contents($invent);
    		$json_decoded2 = json_decode($json_object2);

    		echo $matches[1];     	
    		//var_dump($json_decoded2->rgDescriptions->icon_url);

    		//echo "$json_decoded2->success";
$a = 0;
    		?>
    		<div class="wrapp">
    			<?

    	foreach ($json_decoded2->rgDescriptions as $invent) {
    		
    			if ($a%4==0)
    			{
    					echo "<div class='row'>";
    			}
    	?>

	
    	

    	
			<div class="content">

			 <img src="https://steamcommunity-a.akamaihd.net/economy/image/class/570/ <? echo $invent->classid?>/333fx171f">
				
			 <div class="name"> <? echo $invent->market_hash_name?> </div>
			</div>


		

			<?
$a++;
 			if ($a%4==0)
    			{
    					echo "</div>";
    			}
    		// market_hash_name - название из ТП


    		//echo " <img src = 'https://steamcommunity-a.akamaihd.net/economy/image/class/730/$invent->classid/333fx171f'> " ;

    		}
    		?>
    	</div>
    	<?

    		foreach ($json_decoded->response->players as $player) {
    		echo $player->realname;
    		}
    	}


    	else{

    		echo "USER IS NOT LOGGED IN \n";
    	}
  
}} catch(ErrorException $e) {
    echo $e->getMessage();
}
/*

$api = "AB496973E73A221D8B9B81C43E4DC6E7";

$OpenID = new LightOpenID("http://steam.ru/");



if(!$OpenID->mode) {

if(isset($_GET['login'])) {
 	$OpenID->identity = "http://steamcommunity.com/openid";
 		header("Location: {$OpenID->authUrl()}");
}

if(!isset($_SESSION['T2SteamAuth'])) {
 	$login = "<div id =\"login\">Welcome Guest. Please <a href ='?login'> asdasd  </a> to *Website Action*. </div>";
 }
} elseif($OpenID->mode == "cancel"){

	 echo "Use has cancel";
} else {

if(!isset($_SESSION['T2SteamAuth'])) {
$_SESSION['T2SteamAuth'] = $OpenID->validate() ? $OpenID->identity: null;
$_SESSION['T2SteamID64'] =str_replace("http://steamcommunity.com/openid/id/","",$_SESSION['TF2SteamAuth']);

if($_SESSION['T2SteamAuth'] !== null) {

$Steam64 = str_replace("http://steamcommunity.com/openid/id/", "", $_SESSION['TF2SteamAuth']);
$profile = file_get_contents("http://api.steampowered.com/IsteamUser/GetPlayerSummaries/v0002/?key={$api}&steamids=($Steam64)");
$buffer = fopen("cache/{$Steam64}.json", "w+");
fwrite($buffer, $profile);
fclose($buffer);


}

header("Location: index.php");

}

}
if(isset($_SESSION['T2SteamAuth'])) {


	$login =  "<a href ='?logout'>Steam LogOut</a>";
}

if(isset($_GET['logout'])) {
echo "asdasd";
unset($_SESSION['T2SteamAuth']);
unset($_SESSION['T2SteamID64']);
header("Location: index.php");


}
echo $login;
*/


 ob_end_flush();
?>



</body>
</html>