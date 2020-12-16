<?php
use CRM_Cdashtabs_ExtensionUtil as E;

class CRM_Cdashtabs_Page_Section extends CRM_Core_Page {

  public $useLivePageJS = TRUE;

  /**
   * The action links that we need to display for the browse screen.
   *
   * @var array
   */
  public static $_links = NULL;

  /**
   * Get action Links.
   *
   * @return array
   *   (reference) of action links
   */
  public function &links() {
    if (!(self::$_links)) {
      self::$_links = array(
        CRM_Core_Action::UPDATE => array(
          'name' => E::ts('Edit'),
          'url' => 'civicrm/admin/cdashtabs/section',
          'qs' => 'action=update&id=%%id%%&reset=1',
          'title' => E::ts('Edit Contact Dashboard Tab'),
        ),
      );
    }
    return self::$_links;
  }

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Contact Dashboard Tabs: Sections'));

    $action = CRM_Utils_Request::retrieve('action', 'String',
      // default to 'browse'
      $this, FALSE, 'browse'
    );

    $groupParams = array('name' => 'cdashtabs');
    $rows = CRM_Core_OptionValue::getRows($groupParams, $this->links(), 'component_id,weight');
    $gName = 'cdashtabs';
    $returnURL = CRM_Utils_System::url("civicrm/admin/cdashtabs/section",
      "reset=1"
    );
    CRM_Utils_Weight::addOrder($rows, 'CRM_Core_DAO_OptionValue',
      'id', $returnURL, NULL
    );
    // Array of user dashboard items configured for display.
    $nativeUserDashboardOptions = CRM_Utils_Array::explodePadded(Civi::settings()->get('user_dashboard_options'));

    foreach ($rows as $key => $row) {
      // OptionValue name will be in a format like 'native_1', 'profile_17', etc.
      $optionValueNameParts = explode('_', $row['name']);
      $optionId = array_pop($optionValueNameParts);
      $type = implode('_', $optionValueNameParts);

      $rows[$key]['type'] = ($type === 'uf_group' ? 'Profile' : ucfirst($type));

      if ($type == 'native') {
        $nativeDetails = CRM_Cdashtabs_Settings::getUserDashboardOptionsDetails($optionId);
        $rows[$key]['sectionId'] = $nativeDetails['sectionId'];

        if (!in_array($optionId, $nativeUserDashboardOptions)) {
          unset($rows[$key]);
        }
      }
      elseif ($type == 'uf_group') {
        // Remove ufgroup option value in section list if is_cdash is null
        $cdashtabsValues = json_decode(CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $key, 'value'));
        $rows[$key]['sectionId'] = $optionId;
        $rows[$key]['label'] = CRM_Cdashtabs_Settings::getProfileDisplayTitle($optionId);

        if (!$cdashtabsValues->is_cdash) {
          $rows[$key]['is_active'] = FALSE;
          continue;
        }

        // Remove from sections list if the profile doesn't exist anymore, which can happen
        // if profile is deleted, because we can't clean up the optionValue because there's
        // no uggroup post hook (yet).
        $uFGroup = \Civi\Api4\UFGroup::get()
          ->addWhere('id', '=', $optionId)
          ->addOrderBy('id', 'DESC')
          ->execute()
          ->first();
        if (!$uFGroup) {
          unset($rows[$key]);
        }
      }
    }

    $this->assign('action', $action);
    $this->assign('rows', $rows);

    if ($action & (CRM_Core_Action::UPDATE | CRM_Core_Action::ADD)) {
      $this->edit($action);
    }

    parent::run();
  }

  /**
   * Respond to add/update action.
   */
  public function edit($action) {
    // create a simple controller for editing custom data
    $controller = new CRM_Core_Controller_Simple('CRM_Cdashtabs_Form_Section', E::ts('Contact Dashboard Tabs: Sections'), $action);

    // set the userContext stack
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/admin/cdashtabs/section/', 'reset=1'));
    $controller->setEmbedded(TRUE);
    $controller->process();
    $controller->run();
  }

}
