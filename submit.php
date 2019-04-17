<?php
function httpPost($url, $data)
{
    $curl = curl_init($url);
    /* INCASE SSL DOESN'T WORK
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);*/
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    if ($response === false) {
        throw new Exception(curl_error($curl), curl_errno($curl));
    }
    curl_close($curl);
    return $response;
}

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { //Cloudflare
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

$post_data = http_build_query(
    array(
        'secret' => "RECAPTCHA_SECRET",
        'response' => $_POST['g-recaptcha-response'],
        'remoteip' => $_SERVER['REMOTE_ADDR']
    )
);
$opts = array(
    'http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $post_data
    )
);
$context  = stream_context_create($opts);
$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
$result = json_decode($response);
if (!$result->success) {
    throw new Exception('CAPTCHA verification failed.', 1);
}
httpPost("GOOGLE_SHEETS_LINK",$_POST);
?>
<script>
    window.location.replace("/");
</script>