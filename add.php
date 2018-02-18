<?php


/*function getHash($data, $algo = 'CRC32') {
    return strtr(rtrim(base64_encode(pack('H*', $algo($data))), '='), '+/', '-_');
}

if(!empty($_POST['code']) && isset($_POST['code']) && ($_POST['code'] != "")){

    if(!empty($_POST['pass']) && isset($_POST['pass']) && ($_POST['pass'] != "")){
        $code = htmlspecialchars(*/

echo htmlentities($_POST['code']);