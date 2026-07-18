<?php

/*
==========================================================
TRC4 Broadcast Stream Inspector

📡 Parse M3U8 playlist
🎞 Resolution
🔊 Audio tracks
📶 Bandwidth
🔐 Encryption AES-128
⏱ Segment duration
🌍 CDN Detection
📦 Playlist size
🟢 Stream Quality Badge


Advanced M3U8 Analyzer Engine
Playlist • Quality • Audio • Encryption • CDN Detection

Në këtë pjesë e kthejmë checker-in nga një kontrollues linku në një M3U8 Analyzer.

✅ Lexim real i playlistës .m3u8
✅ Detect Master Playlist
✅ Detect Resolution
✅ Detect Bandwidth
✅ Detect Audio Tracks
✅ Detect Encryption AES-128
✅ Segment Count
✅ Segment Duration
✅ CDN Provider Detection
✅ JSON Output për UI
Advanced M3U8 Analyzer
==========================================================
*/

declare(strict_types=1);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin:*");

$input = json_decode(file_get_contents("php://input"),true);
$url = $input['url'] ?? 'https://abr.de1se01.v2beat.live/playlist.m3u8';

if(!$url)
{

echo json_encode([
"success" => false,
"message" => "Missing URL"
]);
exit;
}

/*
==========================================================
FETCH PLAYLIST
==========================================================
*/

function fetchPlaylist(string $url)
{

$ch = curl_init();

curl_setopt_array($ch,
[
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_TIMEOUT=>15,
//CURLOPT_SSL_VERIFYPEER=>false,
//SECURITY CLEANUP
CURLOPT_SSL_VERIFYPEER => true,
CURLOPT_SSL_VERIFYHOST => 2,
CURLOPT_USERAGENT => "TRC4 Analyzer"
]
);

$data = curl_exec($ch);
curl_close($ch);
return $data ?: "";
}

/*
==========================================================
CDN DETECTOR
==========================================================
*/

function detectCDN(string $url):string
{

$host = parse_url($url,PHP_URL_HOST);

if(!$host)
return "Unknown";

$patterns = [
"cloudfront" => "Amazon CloudFront",
"akamai" => "Akamai",
"fastly" => "Fastly",
"cloudflare" => "Cloudflare",
"azure" => "Azure CDN",
"bunny" => "Bunny CDN",
"v2beat" => "V2Beat"
];

foreach($patterns as $key=>$name)
{

if(stripos($host,$key)!==false)
return $name;
}

return "Unknown CDN";
}

/*
==========================================================
M3U8 ANALYZER
==========================================================
*/

function analyzeM3U8(string $url):array
{

$content = fetchPlaylist($url);
$result = [
"url" => $url,
"type" => "unknown",
"master" => false,
"variants" => [],
"audio" => [],
"encrypted" => false,
"segments" => 0,
"duration" => 0,
"cdn" => detectCDN($url)
];

if(!$content)
{

$result["error"]="Unable to Read Playlist";
return $result;
}

/*
==========================================================
MASTER PLAYLIST
==========================================================
*/

if(stripos($content,"#EXT-X-STREAM-INF")!==false
)
{

$result["master"] = true;
$result["type"] = "master";

preg_match_all('/BANDWIDTH=(\d+)/',$content,$bandwidth);

preg_match_all('/RESOLUTION=(\d+x\d+)/',$content,$resolution);

foreach($bandwidth[1] ?? [] as $i=>$bw)
{

$result["variants"][] = [
"bandwidth" => $bw,
"resolution" => $resolution[1][$i] ?? "unknown"
];
}
}
else
{
$result["type"] = "media";
}

/*
==========================================================
AUDIO TRACKS
==========================================================
*/

preg_match_all('/#EXT-X-MEDIA:(.*)/',$content,$audio);

foreach($audio[1] ?? [] as $track)
{
$result["audio"][] = $track;
}

/*
==========================================================
ENCRYPTION
==========================================================
*/

if(stripos($content,"#EXT-X-KEY")!==false)
{

$result["encrypted"] = true;
}

/*
==========================================================
SEGMENTS
==========================================================
*/

preg_match_all('/#EXTINF:([\d\.]+)/',$content,$segments);

$result["segments"] = count($segments[1] ?? []);

foreach($segments[1] ?? [] as $d) {
$result["duration"] +=(float)$d;
}

$result["duration"] = round($result["duration"],2);
return $result;
}

echo json_encode(analyzeM3U8($url),JSON_PRETTY_PRINT |JSON_UNESCAPED_SLASHES);