<?php

require_once 'cdashtabs.civix.php';
// phpcs:disable
use CRM_Cdashtabs_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function cdashtabs_civicrm_buildForm($formName, &$form) {
  // By default, don't add cdashtabs field.
  $isCdash = FALSE;

  // For the Profile edit settings form, add our custom configuration field.
  if ($formName == 'CRM_UF_Form_Group') {
    // Create new field.
    $form->addElement('checkbox', 'is_cdash', E::ts('Display on Contact Dashboard?'));
    $form->addElement('checkbox', 'is_show_pre_post', E::ts('Display pre- and post-help on Contact Dashboard?'));

    // Assign bhfe fields to the template, so our new field has a place to live.
    $tpl = CRM_Core_Smarty::singleton();
    $bhfe = $tpl->get_template_vars('beginHookFormElements');
    if (!$bhfe) {
      $bhfe = array();
    }
    $bhfe[] = 'is_cdash';
    $bhfe[] = 'is_show_pre_post';
    $form->assign('beginHookFormElements', $bhfe);

    // Add javascript that will relocate our field to a sensible place in the form.
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/CRM_UF_Form_Group.js');

    // Set defaults so our field has the right value.
    $gid = $form->getVar('_id');
    if ($gid) {
      $settings = CRM_Cdashtabs_Settings::getSettings($gid, 'uf_group');
      $defaults = array(
        'is_cdash' => $settings['is_cdash'],
        'is_show_pre_post' => $settings['is_show_pre_post'],
      );
      $form->setDefaults($defaults);
    }
  }
  elseif (strpos($formName, 'CRM_Report_Form_') !== FALSE) {
    // Create new field for reports form page
    $form->addElement('checkbox', 'is_cdash', E::ts('Display on Contact Dashboard?'));
    // Assign bhfe fields to the template, so our new field has a place to live.
    $tpl = CRM_Core_Smarty::singleton();
    $bhfe = $tpl->get_template_vars('beginHookFormElements');
    if (!$bhfe) {
      $bhfe = array();
    }
    $bhfe[] = 'is_cdash';
    $form->assign('beginHookFormElements', $bhfe);

    // Add javascript that will relocate our field to a sensible place in the form.
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/CRM_Report_Form.js');

    // Set defaults so our field has the right value.
    $reportId = $form->getVar('_id');
    if ($reportId) {
      $settings = CRM_Cdashtabs_Settings::getSettings($reportId, 'report');
      $defaults = array(
        'is_cdash' => $settings['is_cdash'],
      );
      $form->setDefaults($defaults);
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function cdashtabs_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_UF_Form_Group') {
    $gid = $form->getVar('_id');
    // Get existing settings and add in our is_cdash value. (Because
    // saveAllSettings() assumes we're passing all setting values.
    if (empty($gid)) {
      $uFGroup = \Civi\Api4\UFGroup::get()
        ->addSelect('id')
        ->addWhere('title', '=', $form->_submitValues['title'])
        ->addOrderBy('id', 'DESC')
        ->execute()
        ->first();

      $gid = $uFGroup['id'];
    }

    $settings = CRM_Cdashtabs_Settings::getSettings($gid, 'uf_group');
    $settings['is_cdash'] = $form->_submitValues['is_cdash'];
    $settings['is_show_pre_post'] = $form->_submitValues['is_show_pre_post'];
    CRM_Cdashtabs_Settings::saveAllSettings($gid, $settings, 'uf_group');
  }
}

/**
 * Implements hook_civicrm_post().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_post
 */
function cdashtabs_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op == 'edit' && $objectName == 'ReportInstance') {
    $formValues = unserialize($objectRef->form_values);

    if (empty($formValues)) {
      return;
    }

    $isCdash = !empty($formValues['is_cdash']) ? 1 : 0;
    // Get existing settings and add in our is_cdash value. (Because
    // saveAllSettings() assumes we're passing all setting values.
    $settings = CRM_Cdashtabs_Settings::getSettings($objectId, 'report');
    $settings['is_cdash'] = $isCdash;
    CRM_Cdashtabs_Settings::saveAllSettings($objectId, $settings, 'report');
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function cdashtabs_civicrm_config(&$config) {
  _cdashtabs_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function cdashtabs_civicrm_xmlMenu(&$files) {
  _cdashtabs_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function cdashtabs_civicrm_install() {
  _cdashtabs_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function cdashtabs_civicrm_postInstall() {
  _cdashtabs_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function cdashtabs_civicrm_uninstall() {
  _cdashtabs_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function cdashtabs_civicrm_enable() {
  _cdashtabs_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function cdashtabs_civicrm_disable() {
  _cdashtabs_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function cdashtabs_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _cdashtabs_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function cdashtabs_civicrm_managed(&$entities) {
  _cdashtabs_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function cdashtabs_civicrm_caseTypes(&$caseTypes) {
  _cdashtabs_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function cdashtabs_civicrm_angularModules(&$angularModules) {
  _cdashtabs_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function cdashtabs_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _cdashtabs_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function cdashtabs_civicrm_entityTypes(&$entityTypes) {
  _cdashtabs_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function cdashtabs_civicrm_themes(&$themes) {
  _cdashtabs_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function cdashtabs_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function cdashtabs_civicrm_navigationMenu(&$menu) {
  $pages = array(
    'admin_page' => array(
      'label'      => E::ts('Contact Dashboard Tabs'),
      'name'       => 'Contact Dashboard Tabs',
      'url'        => 'civicrm/admin/cdashtabs/section?reset=1',
      'parent' => array('Administer', 'Customize Data and Screens'),
      'permission' => 'access CiviCRM',
    ),
  );

  foreach ($pages as $item) {
    // Check that our item doesn't already exist.
    $menu_item_search = array('url' => $item['url']);
    $menu_items = array();
    CRM_Core_BAO_Navigation::retrieve($menu_item_search, $menu_items);
    if (empty($menu_items)) {
      $path = implode('/', $item['parent']);
      unset($item['parent']);
      _cdashtabs_civix_insert_navigation_menu($menu, $path, $item);
    }
  }
}

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun
 */
function cdashtabs_civicrm_pageRun(&$page) {
  $pageName = $page->getVar('_name');

  if ($pageName == 'CRM_Contact_Page_View_UserDashBoard') {
    $useTabs = Civi::settings()->get('cdashtabs_use_tabs');
    $nativeUserDashboardOptions = CRM_Utils_Array::explodePadded(Civi::settings()->get('user_dashboard_options'));

    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/cdashtabs-inject.js', 100, 'page-footer');

    if ($useTabs) {
      $optionGroup = \Civi\Api4\OptionGroup::get()
        ->addWhere('name', '=', 'cdashtabs')
        ->addChain('get_optionValue', \Civi\Api4\OptionValue::get()
          ->addWhere('option_group_id', '=', '$id')
          ->addWhere('is_active', '=', TRUE)
          ->addOrderBy('weight', 'ASC'))
        ->execute()
        ->first();

      foreach ($optionGroup['get_optionValue'] as $key => $optionValue) {
        $optionLabel = explode('_', $optionValue['name']);
        $optionValueType = array_shift($optionLabel);
        $optionValueId = end($optionLabel);
        $optionValueDecode = json_decode($optionValue['value']);

        if ($optionValueType != 'native') {
          if (empty($optionValueDecode->is_cdash)) {
            // If it's not native, honor is_cdash setting by skipping this section.
            continue;
          }
        }
        else {
          // If it's native, honor native user_dashboard_options setting by
          // skipping this section if it's not configured for dashboard display.
          $nativeId = $optionValue['value'];
          if (!in_array($nativeId, $nativeUserDashboardOptions)) {
            continue;
          }
        }

        $optionValues[$key]['class'] = $optionValueId;
        $optionValues[$key]['name'] = CRM_Cdashtabs_Settings::getProfileTitle($optionValueId);

        if ($optionValueType == 'native') {
          $optionValues[$key]['name'] = $optionValue['label'];

          //  Get the same class as the user dashboard option base on value
          $nativeDetails = CRM_Cdashtabs_Settings::getUserDashboardOptionsDetails($optionValue['value']);
          $optionValues[$key]['class'] = $nativeDetails['class'];
        }
        else {
          // Exclude from section if didn't exist in ufgroup profile
          // since we can't remove it using cdashtabs_civicrm_post hook
          $uFGroup = \Civi\Api4\UFGroup::get()
            ->addWhere('id', '=', $optionValueId)
            ->addOrderBy('id', 'DESC')
            ->execute()
            ->first();
          if (!$uFGroup) {
            unset($optionValues[$key]);
          }
        }
      }

      $cdashtabs['options'] = $optionValues;
      CRM_Core_Resources::singleton()->addVars('cdashtabs', $cdashtabs);
      CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/cdashtabs.js', 100, 'page-footer');
      CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.cdashtabs', 'css/cdashtabs.css', 100, 'page-header');
    }
  }
}

/**
 * Implements hook_civicrm_alterContent().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterContent
 */
function cdashtabs_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  if ($context == 'page') {
    if ($object->getVar('_name') == 'CRM_Contact_Page_View_UserDashBoard') {
      // Get a list of settings-per-uf-group where is_cdash = TRUE.
      $cdashProfileSettings = CRM_Cdashtabs_Settings::getFilteredSettings(TRUE, 'uf_group');
      $userContactId = NULL;
      if (!empty($cdashProfileSettings)) {
        // We need the current contact ID to display the profiles properly.
        $userContactId = $object->_contactId;
      }

      if (!$userContactId) {
        // If there's no know contact id, we can't display profiles, so return.
        return;
      }
      // Calls to profile page->run() below will change the page title, and
      // there's not much we can do about that. Store the current page title
      // here so we can change it back afterward.
      $originalTitle = CRM_Utils_System::$title;

      // For each of those settings-groups, process the given uf-group for display.
      foreach ($cdashProfileSettings as $cdashProfileSetting) {
        $ufId = $cdashProfileSetting['uf_group_id'];
        $ufGroup = \Civi\Api4\UFGroup::get()
          ->addWhere('id', '=', $ufId)
          ->execute()
          ->first();
        if (!$ufGroup['is_active']) {
          // If profile is disabled, skip it.
          continue;
        }

        $groupTitle = $ufGroup['frontend_title'] ?? $ufGroup['title'];
        $page = new CRM_Profile_Page_Dynamic($userContactId, $ufId, NULL, TRUE);
        $profileContent = $page->run();
        $ufGroupClass = strtolower(str_replace(' ', '-', $ufGroup['id']));

        $tpl = CRM_Core_Smarty::singleton();
        $tpl->assign('profileName', $ufGroupClass);
        $tpl->assign('profileTitle', $ufGroup['title']);
        $tpl->assign('profileContent', $profileContent);
        $cdashContent = $tpl->fetch('CRM/Cdashtabs/snippet/injectedProfile.tpl');
        $content .= $cdashContent;
      }

      // Re-set the page title to original; it probably was chagned above.
      if (isset($originalTitle)) {
        CRM_Utils_System::setTitle($originalTitle);
      }
    }
  }
}

/**
 * Log CiviCRM API errors to CiviCRM log.
 */
function _cdashtabs_log_api_error(API_Exception $e, string $entity, string $action, array $params) {
  $message = "CiviCRM API Error '{$entity}.{$action}': " . $e->getMessage() . '; ';
  $message .= "API parameters when this error happened: " . json_encode($params) . '; ';
  $bt = debug_backtrace();
  $error_location = "{$bt[1]['file']}::{$bt[1]['line']}";
  $message .= "Error API called from: $error_location";
  CRM_Core_Error::debug_log_message($message);
}

/**
 * CiviCRM API wrapper. Wraps with try/catch, redirects errors to log, saves
 * typing.
 */
function _cdashtabs_civicrmapi(string $entity, string $action, array $params, bool $silence_errors = TRUE) {
  try {
    $result = civicrm_api3($entity, $action, $params);
  }
  catch (API_Exception $e) {
    _cdashtabs_log_api_error($e, $entity, $action, $params);
    if (!$silence_errors) {
      throw $e;
    }
  }

  return $result;
}
