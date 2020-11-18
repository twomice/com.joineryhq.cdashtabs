<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Settings {

  public static function getSettings($id, $type) {
    $settingName = "{$type}_settings_{$id}";
    $result = \Civi\Api4\OptionValue::get()
    ->addWhere('option_group_id:name', '=', 'cdashtabs')
    ->addWhere('name', '=', $settingName)
    ->execute();

    $resultValue = CRM_Utils_Array::value(0, $result, array());
    $settingJson = CRM_Utils_Array::value('value', $resultValue, '{}');
    return json_decode($settingJson, TRUE);
  }

  public static function saveAllSettings($id, $settings, $type) {
    $settingName = "{$type}_settings_{$id}";
    $result = \Civi\Api4\OptionValue::get()
    ->addWhere('option_group_id:name', '=', 'cdashtabs')
    ->addWhere('name', '=', $settingName)
    ->execute();

    $createParams = array();

    if ($optionValueId = CRM_Utils_Array::value('id', $result)) {
      $createParams['id'] = $optionValueId;
    }
    else {
      $createParams['name'] = $settingName;
      $createParams['option_group_id'] = "cdashtabs";
      $createParams['label'] = $label;
    }

    // Add uf_group_id to settings. Without this, optionValue.create api was failing
    // to save new settings with a message like "value already exists in the database"
    // if the values for this ufGroup are the same as for some other ufGroup. So by
    // adding uf_group_id, we make it unique to this ufGroup.
    $settings["{$type}_id"] = $id;
    $createParams['value'] = json_encode($settings);

    try {
      civicrm_api3('optionValue', 'create', $createParams);
      return TRUE;
    }
    catch (API_Exception $e) {
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
      if (
        $optionSettings["{$type}_id"]
        && ($isCdash === NULL || ($optionSettings['is_cdash'] ?? 0) == intval($isCdash))
      ) {
        $filteredSettings[] = $optionSettings;
      }
    }
    return $filteredSettings;
  }

  public static function getProfileTitle($id) {
    $title = '';
    $uFGroups = \Civi\Api4\UFGroup::get()
      ->addWhere('id', '=', $id)
      ->setLimit(1)
      ->execute();
    foreach ($uFGroups as $uFGroup) {
      $title = !empty($uFGroup['frontend_title']) ? $uFGroup['frontend_title'] : $uFGroup['title'];
    }

    return $title;
  }

  public static function getUserDashboardOptionsDetails($value) {
    // Use api to get user dashboard options base on value and
    // get the same class for hide/show of the tab
    $details = [];

    $optionValues = \Civi\Api4\OptionValue::get()
      ->addWhere('option_group_id:name', '=', 'user_dashboard_options')
      ->addWhere('value', '=', $value)
      ->setLimit(1)
      ->execute();
    foreach ($optionValues as $optionValue) {
      $optionClass = str_replace(' ', '', $optionValue['name']);
      $details['class'] = strtolower($optionClass);
      $details['sectionId'] = $optionValue['label'];

      if ($optionValue['name'] == trim($optionValue['name']) && strpos($optionValue['name'], ' ') !== false) {
        $details['class'] = lcfirst($optionClass);
      }
    }

    return $details;
  }

}
