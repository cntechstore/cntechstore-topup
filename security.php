<?php

header("X-Frame-Options: SAMEORIGIN");

header("X-Content-Type-Options: nosniff");

header("Referrer-Policy: strict-origin-when-cross-origin");

header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

header(
    "Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; font-src 'self' https: data:; frame-src https:; connect-src 'self' https:;"
);

?>