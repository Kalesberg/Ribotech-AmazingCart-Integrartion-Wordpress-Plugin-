<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Test Android Push Notification</title>
</head>


<?php


// API access key from Google API's Console
define( 'API_ACCESS_KEY','AIzaSyD4agY8aTO2PbGMrHssBFpVcQV_7mia0Cg' );

//here put your reg 
$registrationIds = array("APA91bFpe5U6fCdm7rMSRorNM_LouYFK-wpXyVaRbFC25-iav8yGb9l7ZJBCuY4C83XBcbICNibJHkB3up8xjp6WxKHO_7jtlOYzTU_GFo6ZzT7syO9yNaMmgj6uh0SnIMasVoCPwYtc1CJFVUCStqQvvXf8I69Zfw" );

// prep the bundle
$msg = array
(
    'message'       => 'http://quebuenatulsa.com',
    'title'         => 'La Buena musica',
    'subtitle'      => 'La Mejor musica',
    'tickerText'    => 'La mojor Variedad',
    'vibrate'   => 0,
    'sound'     => 0,
);

$fields = array
(
    'registration_ids'  => $registrationIds,
    'data'              => $msg
);

$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );

echo $result;
?>


<body>
</body>
</html>