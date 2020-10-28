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

    // Assign bhfe fields to the template, so our new field has a place to live.
    $tpl = CRM_Core_Smarty::singleton();
    $bhfe = $tpl->get_template_vars('beginHookFormElements');
    if (!$bhfe) {
      $bhfe = array();
    }
    $bhfe[] = 'is_cdash';
    $form->assign('beginHookFormElements', $bhfe);

    // Add javascript that will relocate our field to a sensible place in the form.
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/CRM_UF_Form_Group.js');

    // Set defaults so our field has the right value.
    $gid = $form->getVar('_id');
    if ($gid) {
      $settings = CRM_Cdashtabs_Settings::getUFGroupSettings($gid);
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
    // saveAllUFGRoupSettings() assumes we're passing all setting values.
    $settings = CRM_Cdashtabs_Settings::getUFGroupSettings($gid);
    $settings['is_cdash'] = $form->_submitValues['is_cdash'];
    CRM_Cdashtabs_Settings::saveAllUFGRoupSettings($gid, $settings);
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
//function cdashtabs_civicrm_navigationMenu(&$menu) {
//  _cdashtabs_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _cdashtabs_civix_navigationMenu($menu);
//}

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun
 */
function cdashtabs_civicrm_pageRun(&$page) {
  $pageName = $page->getVar('_name');

  if ($pageName == 'CRM_Contact_Page_View_UserDashBoard') {
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/cdashtabs-inject.js', 100, 'page-footer');
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cdashtabs', 'js/cdashtabs.js', 100, 'page-footer');
    CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.cdashtabs', 'css/cdashtabs.css', 100, 'page-header');
  }
}

/**
 * Implements hook_civicrm_alterContent().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterContent
 */
function cdashtabs_civicrm_alterContent( &$content, $context, $tplName, &$object ) {
  if($context == 'page') {
    if($tplName == 'CRM\Contact\Page\View\UserDashBoard.tpl') {
      $session = CRM_Core_Session::singleton();
      $allUFGroup = CRM_Core_BAO_UFGroup::getModuleUFGroup('User Account', 0, TRUE);
      $cdashContent = '<div class="cdash-inject" style="display: none;">';

      foreach ($allUFGroup as $uFGroupKey => $uFGroup) {
        $cdash = CRM_Cdashtabs_Settings::getUFGroupSettings($uFGroupKey);

        if (!empty($cdash['is_cdash'])) {
          $profileFields = _cdashtabs_civicrm_getProfileGroupFields($session->get('userID'), $uFGroupKey);
          $uFGroupClass = strtolower(str_replace(' ', '-', $uFGroup['title']));
          $cdashContent .= "<div id='crm-container' class='crm-container cdash-inject-list'>";
          $cdashContent .= "<table><tbody><tr class='crm-dashboard-{$uFGroupClass}'><td>";
          $cdashContent .= "<div class='header-dark'>{$uFGroup['title']}</div>";
          $cdashContent .= "<div class='view-content'>";
          $cdashContent .= "<div class='crm-profile-name-{$uFGroupClass}'>";

          foreach ($profileFields as $uFFieldKey => $uFField) {
            $cdashContent .= "<div id='row-{$uFFieldKey}' class='crm-section {$uFFieldKey}-section'>";
            $cdashContent .= "<div class='label'>{$uFField['label']}</div>";
            $cdashContent .= "<div class='content'>{$uFField['value']}</div><div class='clear'></div>";
            $cdashContent .= "</div>";
          }

          $cdashContent .= "</div></div>";
          $cdashContent .= "</td></tr></tbody></table>";
          $cdashContent .= "</div>";
        }
      }

      $cdashContent .= "</div>";
      $content .= $cdashContent;
    }
  }
}

function _cdashtabs_civicrm_getProfileGroupFields($userID, $uFGroupID) {
  $config = CRM_Core_Config::singleton();
  $allowPermission = FALSE;
  if (CRM_Core_Permission::check('administer users') || CRM_Core_Permission::check('view all contacts') || CRM_Contact_BAO_Contact_Permission::allow($userID)) {
    $allowPermission = TRUE;
  }

  $fields = CRM_Core_BAO_UFGroup::getFields($uFGroupID, FALSE, CRM_Core_Action::VIEW,
    NULL, NULL, FALSE, FALSE,
    FALSE, NULL,
    CRM_Core_Action::VIEW
  );

  // make sure we dont expose all fields based on permission
  $admin = FALSE;
  if ((!$config->userFrameworkFrontend && $allowPermission)) {
    $admin = TRUE;
  }

  //reformat fields array
  foreach ($fields as $name => $field) {
    // also eliminate all formatting fields
    if (CRM_Utils_Array::value('field_type', $field) == 'Formatting') {
      unset($fields[$name]);
    }

    // make sure that there is enough permission to expose this field
    if (!$admin && $field['visibility'] == 'User and User Admin Only') {
      unset($fields[$name]);
    }
  }

  // $profileFields array can be used for customized display of field labels and values in Profile/View.tpl
  $values = [];

  CRM_Core_BAO_UFGroup::getValues($userID, $fields, $values, TRUE, NULL, FALSE, NULL);
  $profileFields = [];
  $labels = [];

  foreach ($fields as $name => $field) {
    //CRM-14338
    // Create a unique, non-empty index for each field.
    $index = $field['title'];
    if ($index === '') {
      $index = ' ';
    }
    while (array_key_exists($index, $labels)) {
      $index .= ' ';
    }

    $labels[$index] = preg_replace('/\s+|\W+/', '_', $name);
  }

  foreach ($values as $title => $value) {
    $profileFields[$labels[$title]] = [
      'label' => $title,
      'value' => $value,
    ];
  }

  return $profileFields;
}
