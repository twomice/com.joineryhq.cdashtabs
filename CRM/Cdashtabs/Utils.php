<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Utils {

  public static function getDashboardBaseUrl() {
    $ret = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $ret = ltrim($ret, '/');
    return $ret;
  }

}
