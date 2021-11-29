<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Utils {

  public static function getCurrentBaseUrl() {
    return $_SERVER['REQUEST_URI'];
  }

  public static function alterUrl($startingUrl, $queryParams = [], $fragment = '') {
    $urlParts = parse_url($startingUrl);
    if (!empty($queryParams)) {
      $startingQueryParams = [];
      parse_str($urlParts['query'], $startingQueryParams);
      $urlParts['query'] = http_build_query(array_merge($startingQueryParams, $queryParams));
    }
    if (!empty($fragment)) {
      $urlParts['fragment'] = $fragment;
    }

    $scheme   = isset($urlParts['scheme']) ? $urlParts['scheme'] . '://' : '';
    $host     = isset($urlParts['host']) ? $urlParts['host'] : '';
    $port     = isset($urlParts['port']) ? ':' . $urlParts['port'] : '';
    $user     = isset($urlParts['user']) ? $urlParts['user'] : '';
    $pass     = isset($urlParts['pass']) ? ':' . $urlParts['pass']  : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($urlParts['path']) ? $urlParts['path'] : '';
    $query    = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';
    $fragment = isset($urlParts['fragment']) ? '#' . $urlParts['fragment'] : '';
    $ret = "$scheme$user$pass$host$port$path$query$fragment";
    return $ret;
  }
}
