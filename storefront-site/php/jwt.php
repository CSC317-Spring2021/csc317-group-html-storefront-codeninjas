<?php
namespace JWT;
require_once $_SERVER['DOCUMENT_ROOT'] . "/php/dotenv.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/php/urlb64.php";

function _sign($payload) {
    $secret = getenv("JWT_SECRET");
    if (empty($secret)) {
        throw new \Exception("No secret configured.");
    }
    return hash_hmac("sha256", $payload, $secret, true);
}

function encode($payload) {
    $expire_time = getenv("JWT_EXPIRE_TIME");
    if (empty($expire_time)) {
        throw new \Exception("No JWT expire time configured.");
    }
    $payload = array_merge($payload, [
        "iat" => time(),
        "exp" => time() + $expire_time
    ]);

    $header = json_encode([
        "typ" => "JWT",
        "alg" => "HS256"
    ]);
    $encoded_payload = json_encode($payload);
    $base64UrlHeader = \URLSafeB64\encode($header);
    $base64UrlPayload = \URLSafeB64\encode($encoded_payload);
    $signature = _sign($base64UrlHeader . "." . $base64UrlPayload);
    $base64UrlSignature = \URLSafeB64\encode($signature);
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

function verify($jwt) {
    [$headerEncoded, $payloadEncoded, $signatureEncoded] = explode(".", $jwt);

    $payload = json_decode(\URLSafeB64\decode($payloadEncoded));
    if (!empty($payload->exp) && time() >= $payload->exp) {
        return false;
    }

    $signature = \URLSafeB64\decode($signatureEncoded);
    $hash = _sign($headerEncoded . "." . $payloadEncoded);

    return hash_equals($signature, $hash);
}

function decode($jwt) {
    if (verify($jwt)) {
        [$headerEncoded, $payloadEncoded, $signatureEncoded] = explode(".", $jwt);

        return json_decode(\URLSafeB64\decode($payloadEncoded));
    } else {
        return false;
    }
}
?>