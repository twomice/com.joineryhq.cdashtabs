<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Settings {

  public static function getSettings($id, $type) {
    $settingName = "{$type}_settings_{$id}";
    $result = civicrm_api3('OptionValue', 'get', array(
      'sequential' => 1,
      'option_group_id' => "cdashtabs",
      'name' => $settingName,
    ));
    $resultValue = CRM_Utils_Array::value(0, $result['values'], array());
    $settingJson = CRM_Utils_Array::value('value', $resultValue, '{}');
    return json_decode($settingJson, TRUE);
  }

  public static function saveAllSettings($id, $settings, $type) {
    $settingName = "{$type}_settings_{$id}";
    $result = civicrm_api3('OptionValue', 'get', array(
      'sequential' => 1,
      'option_group_id' => "cdashtabs",
      'name' => $settingName,
    ));

    $createParams = array();

    if ($optionValueId = CRM_Utils_Array::value('id', $result)) {
      $createParams['id'] = $optionValueId;
    }
    else {
      $createParams['name'] = $settingName;
      $createParams['option_group_id'] = "cdashtabs";
    }

    // Add uf_group_id to settings. Without this, optionValue.create api was failing
    // to save new settings with a message like "value already exists in the database"
    // if the values for this ufGroup are the same as for some other ufGroup. So by
    // adding uf_group_id, we make it unique to this ufGroup.
    $settingType = $type === 'ufgroup' ? 'uf_group' : $type;
    $settings["{$settingType}_id"] = $id;
    $createParams['value'] = json_encode($settings);

    try {
      civicrm_api3('optionValue', 'create', $createParams);
      return TRUE;
    }
    catch (CiviCRM_API3_Exception $e) {
      return FALSE;
    }
  }

  /**
   * Get an array of saved settings-per-uf-group, filtered per the given criteria.
   *
   * @param Boolean $isCdash If given, filter only for settings-per-uf-group where
   *    the setting value is_cdash matches the given value.
   *
   */
  public static function getFilteredSettings($isCdash = NULL, $type) {
    $filteredSettings = [];
    $optionGroup = \Civi\Api4\OptionGroup::get()
      ->addWhere('name', '=', 'cdashtabs')
      ->addChain('get_optionValue', \Civi\Api4\OptionValue::get()->addWhere('option_group_id', '=', '$id')->addOrderBy('weight', 'ASC'))
      ->execute()
      ->first();
    foreach ($optionGroup['get_optionValue'] as $optionValue) {
      $optionSettingJson = $optionValue['value'] ?? '{}';
      $optionSettings = json_decode($optionSettingJson, TRUE);
      $settingType = $type === 'ufgroup' ? 'uf_group' : $type;
      if (
        $optionSettings["{$settingType}_id"]
        && ($isCdash === NULL || ($optionSettings['is_cdash'] ?? 0) == intval($isCdash))
      ) {
        $filteredSettings[] = $optionSettings;
      }
    }
    return $filteredSettings;
  }

  public static function getTitleTypeSettings($id, $type) {
    $title = '';
    if ($type === 'ufgroup') {
      $uFGroups = \Civi\Api4\UFGroup::get()
        ->addWhere('id', '=', $id)
        ->setLimit(1)
        ->execute();
      foreach ($uFGroups as $uFGroup) {
        $title = $uFGroup['title'];
      }
    } elseif ($type === 'report') {
      // civicrm_report_instance (no api)
    } elseif ($type === 'native') {
      $userDashboardOptionId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup',
        'user_dashboard_options',
        'id',
        'name'
      );

      $optionValues = \Civi\Api4\OptionValue::get()
        ->addWhere('option_group_id', '=', $userDashboardOptionId)
        ->addWhere('value', '=', $id)
        ->setLimit(1)
        ->execute();
      foreach ($optionValues as $optionValue) {
        $title = $optionValue['label'];
      }
    }

    return $title;
  }

  public static function getUserDashboardOptionsClass($value) {
    // Use api to get user dashboard options base on value and
    // get the same class for hide/show of the tab
    $class = '';
    $userDashboardOptionId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup',
      'user_dashboard_options',
      'id',
      'name'
    );

    $optionValues = \Civi\Api4\OptionValue::get()
      ->addWhere('option_group_id', '=', $userDashboardOptionId)
      ->addWhere('value', '=', $value)
      ->setLimit(1)
      ->execute();
    foreach ($optionValues as $optionValue) {
      $optionClass = str_replace(' ', '', $optionValue['name']);
      $class = strtolower($optionClass);

      if ($optionValue['name'] == trim($optionValue['name']) && strpos($optionValue['name'], ' ') !== false) {
        $class = lcfirst($optionClass);
      }
    }

    return $class;
  }

}
