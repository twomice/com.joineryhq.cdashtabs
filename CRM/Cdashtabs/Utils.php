<?php

// phpcs:disable
use CRM_Cdashtabs_ExtensionUtil as E;
// phpcs:enable

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
    $pass     = isset($urlParts['pass']) ? ':' . $urlParts['pass'] : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = isset($urlParts['path']) ? $urlParts['path'] : '';
    $query    = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';
    $fragment = isset($urlParts['fragment']) ? '#' . $urlParts['fragment'] : '';
    $ret = "$scheme$user$pass$host$port$path$query$fragment";
    return $ret;
  }

  public static function addFieldsToProfileForm($form, $prefixNames = FALSE) {
    $ret = [];

    if ($prefixNames) {
      $prefix = 'cdashtabs_';
    }

    $fieldNameIsCdash = $prefix . 'is_cdash';
    $form->addElement('checkbox', $fieldNameIsCdash, E::ts('Display on Contact Dashboard?'));
    $ret[] = $fieldNameIsCdash;

    $fieldNameContactType = $prefix . 'cdash_contact_type';
    $contactTypeOptions = CRM_Contact_BAO_ContactType::getSelectElements(FALSE, FALSE);
    $form->add('select', $fieldNameContactType, E::ts('Display only for contacts of type(s)'), $contactTypeOptions, FALSE, [
      'multiple' => 'multiple',
      'class' => 'crm-select2',
      'placeholder' => E::ts('Select contact types'),
    ]);
    $ret[] = $fieldNameContactType;

    $fieldNameGroup = $prefix . 'group';
    $groupOptions = CRM_Core_PseudoConstant::nestedGroup(TRUE, NULL, TRUE, "plain");
    $form->add('select', $fieldNameGroup, E::ts('Display only for contacts in group(s)'), $groupOptions, FALSE, [
      'multiple' => 'multiple',
      'class' => 'crm-select2',
      'placeholder' => E::ts('Select groups'),
    ]);
    $ret[] = $fieldNameGroup;

    $fieldNameIsShowPrePost = $prefix . 'is_show_pre_post';
    $form->addElement('checkbox', $fieldNameIsShowPrePost, E::ts('Display pre- and post-help on Contact Dashboard?'));
    $ret[] = $fieldNameIsShowPrePost;

    $fieldNameIsEdit = $prefix . 'is_edit';
    $form->addElement('checkbox', $fieldNameIsEdit, E::ts('Provide "Edit" button?'));
    $ret[] = $fieldNameIsEdit;

    return $ret;
  }

  public static function stripProfileFormFieldPrefix($fieldName) {
    return preg_replace('/^cdashtabs_/', '', $fieldName);
  }

  /**
   * Check whether a string value will fit into a VARCHAR column.
   *
   * @param string $value
   * @param string $tableName
   * @param string $columnName
   *
   * @return bool
   *   TRUE if $value will fit; FALSE otherwise.
   *
   * @throws CRM_Core_Exception
   *   If the table/column is invalid, missing, or not VARCHAR.
   */
  public static function checkVarcharValueLength(string $value, string $tableName, string $columnName): bool {

    static $cache = [];

    $cacheKey = "{$tableName}.{$columnName}";

    if (!isset($cache[$cacheKey])) {
      // Extremely conservative identifier validation.
      // Prevents SQL injection since identifiers cannot be parameterized.
      foreach ([$tableName, $columnName] as $identifier) {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $identifier)) {
          throw new CRM_Core_Exception(
            "Invalid SQL identifier: {$identifier}"
          );
        }
      }

      // Get schema details for this table.column.
      $query = "
        SELECT
          DATA_TYPE,
          CHARACTER_MAXIMUM_LENGTH
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = %1
          AND COLUMN_NAME = %2
      ";

      $dao = CRM_Core_DAO::executeQuery($query, [
        1 => [$tableName, 'String'],
        2 => [$columnName, 'String'],
      ]);

      if (!$dao->fetch()) {
        throw new CRM_Core_Exception(
          "Unable to determine schema for {$tableName}.{$columnName}"
        );
      }

      if (strtolower($dao->DATA_TYPE) !== 'varchar') {
        throw new CRM_Core_Exception(
          "{$tableName}.{$columnName} is not a VARCHAR column"
        );
      }

      $maxLength = $dao->CHARACTER_MAXIMUM_LENGTH;

      if (!is_numeric($maxLength) || $maxLength <= 0) {
        throw new CRM_Core_Exception(
          "Invalid VARCHAR length for {$tableName}.{$columnName}"
        );
      }

      $cache[$cacheKey] = (int) $maxLength;
    }

    $maxLength = $cache[$cacheKey];

    return (mb_strlen($value, 'UTF-8') <= $maxLength);
  }

  /**
   * Throw an exeption if settings data will be too long.
   *
   * @param string $settingsJson
   * @throws CRM_Core_Exception
   */
  public static function validateSettingsLength(string $settingsJson) {
    if (!self::checkVarcharValueLength($settingsJson, 'civicrm_option_value', 'value')) {
      throw new CRM_Core_Exception(
        "Contact Dashboard Tabs: The given settings are too long to be saved. (Hint: too many multi-select options selected?)"
      );
    }
  }

}
