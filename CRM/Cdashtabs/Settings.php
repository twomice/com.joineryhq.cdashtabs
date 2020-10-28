<?php

/**
 * Settings-related utility methods.
 *
 */
class CRM_Cdashtabs_Settings {

  public static function getUFGroupSettings($ufGroupId) {
    $settingName = "ufgroup_settings_{$ufGroupId}";
    $result = civicrm_api3('OptionValue', 'get', array(
      'sequential' => 1,
      'option_group_id' => "cdashtabs",
      'name' => $settingName,
    ));
    $resultValue = CRM_Utils_Array::value(0, $result['values'], array());
    $settingJson = CRM_Utils_Array::value('value', $resultValue, '{}');
    return json_decode($settingJson, TRUE);
  }

  public static function saveAllUFGRoupSettings($ufGroupId, $settings) {
    $settingName = "ufgroup_settings_{$ufGroupId}";
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
    $settings['uf_group_id'] = $ufGroupId;
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
  public static function getFilteredUFGroupSettings($isCdash = NULL) {
    $filteredSettings = [];
    $optionGroup = \Civi\Api4\OptionGroup::get()
      ->addWhere('name', '=', 'cdashtabs')
      ->addChain('get_optionValue', \Civi\Api4\OptionValue::get()->addWhere('option_group_id', '=', '$id'))
      ->execute()
      ->first();
    foreach ($optionGroup['get_optionValue'] as $optionValue) {
      $optionSettingJson = $optionValue['value'] ?? '{}';
      $optionSettings = json_decode($optionSettingJson, TRUE);
      if (
        $optionSettings['uf_group_id']
        && ($isCdash === NULL || ($optionSettings['is_cdash'] ?? 0) == intval($isCdash))
      ) {
        $filteredSettings[] = $optionSettings;
      }
    }
    return $filteredSettings;
  }

}
