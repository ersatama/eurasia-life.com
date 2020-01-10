<?php

$fp = fsockopen("146.158.79.90", 25, $errno, $errstr, 10);

if (!$fp) {
    echo "$errstr ($errno)";
} else {
	echo "ok";
    fclose($fp);
}
