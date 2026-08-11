<?php
$n = 9.223372036854776E+18;
var_dump($n < PHP_INT_MIN || $n > PHP_INT_MAX);
var_dump(intval($n));
