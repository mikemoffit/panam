<?php
if (!defined('ABSPATH')) exit;

/**
 * Color utilities (hex <-> rgb, mix, luminance, contrast)
 */
function panam_hex_to_rgb(string $hex): array {
  $hex = ltrim($hex, '#');
  if (strlen($hex) === 3) {
    $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
  }
  return [
    hexdec(substr($hex, 0, 2)),
    hexdec(substr($hex, 2, 2)),
    hexdec(substr($hex, 4, 2)),
  ];
}

function panam_rgb_to_hex(int $r, int $g, int $b): string {
  $r = max(0, min(255, $r));
  $g = max(0, min(255, $g));
  $b = max(0, min(255, $b));
  return sprintf('#%02x%02x%02x', $r, $g, $b);
}

function panam_mix(string $hexA, string $hexB, float $t): string {
  [$r1,$g1,$b1] = panam_hex_to_rgb($hexA);
  [$r2,$g2,$b2] = panam_hex_to_rgb($hexB);
  $r = (int) round($r1 + ($r2 - $r1) * $t);
  $g = (int) round($g1 + ($g2 - $g1) * $t);
  $b = (int) round($b1 + ($b2 - $b1) * $t);
  return panam_rgb_to_hex($r,$g,$b);
}

function panam_lighten(string $hex, float $percent): string {
  return panam_mix($hex, '#ffffff', max(0, min(1, $percent / 100)));
}

function panam_darken(string $hex, float $percent): string {
  return panam_mix($hex, '#000000', max(0, min(1, $percent / 100)));
}

function panam_rel_luminance(string $hex): float {
  [$r,$g,$b] = panam_hex_to_rgb($hex);
  $srgb = [$r/255, $g/255, $b/255];
  $lin = array_map(function($c){
    return ($c <= 0.03928) ? ($c/12.92) : pow((($c+0.055)/1.055), 2.4);
  }, $srgb);
  return 0.2126*$lin[0] + 0.7152*$lin[1] + 0.0722*$lin[2];
}

function panam_contrast_ratio(string $hexA, string $hexB): float {
  $l1 = panam_rel_luminance($hexA);
  $l2 = panam_rel_luminance($hexB);
  $lighter = max($l1, $l2);
  $darker  = min($l1, $l2);
  return ($lighter + 0.05) / ($darker + 0.05);
}

/**
 * Choose readable text color for a background.
 * Prefers higher contrast between #111827 and #ffffff.
 */
function panam_on_color(string $bg): string {
  $dark = '#111827';
  $light = '#ffffff';
  return (panam_contrast_ratio($bg, $dark) >= panam_contrast_ratio($bg, $light)) ? $dark : $light;
}

/**
 * Build a tint scale similar to 50..900.
 */
function panam_scale(string $base): array {
  return [
    '50'  => panam_lighten($base, 92),
    '100' => panam_lighten($base, 84),
    '200' => panam_lighten($base, 72),
    '300' => panam_lighten($base, 56),
    '400' => panam_lighten($base, 36),
    '500' => $base,
    '600' => panam_darken($base, 10),
    '700' => panam_darken($base, 22),
    '800' => panam_darken($base, 34),
    '900' => panam_darken($base, 46),
  ];
}