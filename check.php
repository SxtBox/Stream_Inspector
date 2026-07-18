<?php
/*
==========================================================
TRC4 Broadcast Stream Inspector
Funksionet:

✅ cURL request
✅ HTTP Status
✅ Response Time
✅ Redirect Detection
✅ CORS Detection
✅ Server Detection
✅ Content-Type
✅ SSL Check
✅ JSON Output

PHP cURL Stream Checker Engine
cURL Stream Checker + JSON API
✅ Frontend
✅ Neon UI
✅ JS Engine
✅ PHP API
✅ Real URL Checking
==========================================================
*/

declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

/*
==========================================================
READ JSON INPUT
==========================================================
*/

$input = json_decode(file_get_contents("php://input"),true);
$url = $input['url'] ?? 'https://abr.de1se01.v2beat.live/playlist.m3u8';

if(!$url)
{
echo json_encode([
"status" => "offline",
"message" => "Missing URL"
]);
exit;
}
/*
==========================================================
URL VALIDATION
==========================================================
*/

if(!filter_var($url,FILTER_VALIDATE_URL))
{

echo json_encode([
"url" => $url,
"status" => "offline",
"message" => "Invalid URL"
]);
exit;
}

/*
==========================================================
STREAM CHECK FUNCTION
==========================================================
*/

function checkStream(string $url):array
{

$start = microtime(true);
$ch = curl_init();
curl_setopt_array(
$ch,
[
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HEADER => true,
CURLOPT_NOBODY => false,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_MAXREDIRS => 5,
CURLOPT_CONNECTTIMEOUT => 10,
CURLOPT_TIMEOUT => 20,
//CURLOPT_SSL_VERIFYPEER => false,
//CURLOPT_SSL_VERIFYHOST => false,
//SECURITY CLEANUP
CURLOPT_SSL_VERIFYPEER => true,
CURLOPT_SSL_VERIFYHOST => 2,
CURLOPT_USERAGENT => "TRC4 Broadcast Inspector/1.0"
]
);

$response = curl_exec($ch);
$time = round((microtime(true)-$start)*1000);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

/*
==========================================================
DEFAULT RESULT
==========================================================
*/

$result=[
"url" => $url,
"http" => $info['http_code'] ?? 0,
"time" => $time,
"status" => "offline",
"cors" => false,
"server" => "",
"type" => "",
"redirect" => false,
"ssl" => false,
];

if($error)
{

$result["error"] = $error;
return $result;
}

/*
==========================================================
STATUS DETECTION
==========================================================
*/

if($result["http"]>=200 && $result["http"]<300)
{
$result["status"] = "live";
}

elseif($result["http"]>=300 && $result["http"]<400)
{
$result["status"] = "redirect";
}

/*
==========================================================
HEADER PARSING
==========================================================
*/

$headerSize = $info['header_size'] ?? 0;
$headers = substr($response,0,$headerSize);

foreach(explode("\r\n",$headers)as $line
)
{

if(stripos($line,"Access-Control-Allow-Origin")!==false) {
$result["cors"] = true;
}

if(stripos($line,"Server:")===0) {
$result["server"] = trim(str_replace("Server:","",$line));
}

if(stripos($line,"Content-Type:")===0) {
$result["type"] = trim(str_replace("Content-Type:","",$line));
}

}

/*
==========================================================
SSL DETECTION
==========================================================
*/

if(str_starts_with($url,"https://")) {
$result["ssl"] = true;
}

/*
==========================================================
M3U8 QUICK ANALYSIS
==========================================================
*/

if(stripos($result["type"],"mpegurl")!==false||stripos($url,".m3u8")!==false) {
$result["hls"] = true;
}
else
{
$result["hls"] = false;
}
return $result;
}

/*
==========================================================
RUN CHECK
==========================================================
*/

echo json_encode(checkStream($url),JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;
?>