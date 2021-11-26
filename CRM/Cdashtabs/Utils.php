<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Utils {

  public static function getDashboardBaseUrl($reset = FALSE) {
    if($reset) {
      $queryParams = ['reset' => 1];
    }

    // Special handling for wordpress:
    $isWpFrontend = FALSE;
    if (CRM_Core_Config::singleton()->userFramework == 'WordPress') {
      $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      if (!preg_match('/^\/wp-admin\b/', $urlPath)) {
        $isWpFrontend = TRUE;
      }
    }
    if ($isWpFrontend) {
      $ret = $urlPath;
      if (!empty($queryParams)) {
        $ret .= '?' . http_build_query($queryParams);
      }
    }
    else {
      $path = 'civicrm/user';
      $ret = CRM_Utils_System::url($path, $queryParams);
    }
    return $ret;
  }

}
