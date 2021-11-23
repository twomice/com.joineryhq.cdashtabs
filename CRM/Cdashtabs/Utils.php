<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Utils {
  public static function getDashboardBaseUrl() {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?reset=1';
  }
}
