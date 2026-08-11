<?php

$exports['fromStringImpl'] = function($just, $nothing, string $s) {
  $s = \trim($s);
  if ($s === "") return $nothing;
  
  // Handle prefix notations
  if (\strpos($s, '0b') === 0 || \strpos($s, '0B') === 0) {
    $s = \substr($s, 2);
    if (!\preg_match('/^[01]+$/', $s)) return $nothing;
    return $just(\gmp_init($s, 2));
  }
  if (\strpos($s, '0o') === 0 || \strpos($s, '0O') === 0) {
    $s = \substr($s, 2);
    if (!\preg_match('/^[0-7]+$/', $s)) return $nothing;
    return $just(\gmp_init($s, 8));
  }
  if (\strpos($s, '0x') === 0 || \strpos($s, '0X') === 0) {
    $s = \substr($s, 2);
    if (!\preg_match('/^[0-9a-fA-F]+$/', $s)) return $nothing;
    return $just(\gmp_init($s, 16));
  }
  
  // Check if it's a valid decimal, optionally with an exponent
  if (\preg_match('/^([-+]?\d+)(?:[eE]([-+]?\d+))?$/', $s, $matches)) {
    $base = $matches[1];
    if (isset($matches[2])) {
      $exp = (int)$matches[2];
      if ($exp < 0) {
      } else {
         $base .= \str_repeat("0", $exp);
      }
    }
    return $just(\gmp_init($base, 10));
  }
  
  return $nothing;
};

$exports['fromNumberImpl'] = function($just, $nothing, $n) {
  if (!\is_finite($n)) return $nothing;
  $s = \number_format(\floor($n), 0, '.', '');
  return $just(\gmp_init($s, 10));
};

$exports['fromInt'] = function(int $n) {
  return \gmp_init($n, 10);
};

$exports['toNumber'] = function($n) {
  return (float)\gmp_strval($n, 10);
};

$exports['toString'] = function($n) {
  return \gmp_strval($n, 10);
};

$exports['biAdd'] = function($a, $b) {
  return \gmp_add($a, $b);
};

$exports['biMul'] = function($a, $b) {
  return \gmp_mul($a, $b);
};

$exports['biZero'] = \gmp_init(0, 10);
$exports['biOne'] = \gmp_init(1, 10);

$exports['biSub'] = function($a, $b) {
  return \gmp_sub($a, $b);
};

$exports['biMod'] = function($a, $b) {
  return \gmp_mod($a, $b);
};

$exports['biDiv'] = function($a, $b) {
  return \gmp_div_q($a, $b);
};

$exports['biDegree'] = function($a) {
  return (int)\gmp_intval(\gmp_abs($a));
};

$exports['pow'] = function($a, $b) {
  return \gmp_pow($a, (int)\gmp_intval($b));
};

$exports['or'] = function($a, $b) {
  return \gmp_or($a, $b);
};

$exports['not'] = function($a) {
  return \gmp_com($a);
};

$exports['xor'] = function($a, $b) {
  return \gmp_xor($a, $b);
};

$exports['and'] = function($a, $b) {
  return \gmp_and($a, $b);
};

$exports['shl'] = function($a, $b) {
  return \gmp_mul($a, \gmp_pow(2, (int)\gmp_intval($b)));
};

$exports['shr'] = function($a, $b) {
  return \gmp_div_q($a, \gmp_pow(2, (int)\gmp_intval($b)));
};

$exports['biEquals'] = function($a, $b) {
  return \gmp_cmp($a, $b) === 0;
};

$exports['biCompare'] = function($a, $b) {
  return \gmp_cmp($a, $b);
};

$exports['fromStringAsImpl'] = function($just, $nothing, $radix, string $s) {
  $s = \trim($s);
  if ($s === "") return $nothing;
  try {
    return $just(\gmp_init($s, $radix));
  } catch (\Throwable $e) {
    return $nothing;
  }
};

$exports['fromTypeLevelInt'] = function(string $s) {
  return \gmp_init($s, 10);
};

$exports['asIntN'] = function(int $n, $b) {
  return \gmp_and($b, \gmp_sub(\gmp_pow(2, $n), 1));
};

$exports['asUintN'] = function(int $n, $b) {
  return \gmp_and($b, \gmp_sub(\gmp_pow(2, $n), 1));
};

$exports['toStringAs'] = function(int $radix, $n) {
  return \gmp_strval($n, $radix);
};

return $exports;
