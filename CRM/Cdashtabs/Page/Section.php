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
          'name' => ts('Edit'),
          'url' => 'civicrm/admin/cdashtabs/section',
          'qs' => 'action=update&id=%%id%%&reset=1',
          'title' => ts('Edit Contact Dashboard Tab'),
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
    $optionValue = CRM_Core_OptionValue::getRows($groupParams, $this->links(), 'component_id,weight');
    $gName = 'cdashtabs';
    $returnURL = CRM_Utils_System::url("civicrm/admin/cdashtabs/section",
      "reset=1"
    );
    CRM_Utils_Weight::addOrder($optionValue, 'CRM_Core_DAO_OptionValue',
      'id', $returnURL, NULL
    );
    $nativeUserDashboardOptions = CRM_Utils_Array::explodePadded(Civi::settings()->get('user_dashboard_options'));

    foreach ($optionValue as $key => $option) {
      $optionLabel = explode('_', $option['name']);
      $type = array_shift($optionLabel);
      $optionId = end($optionLabel);
      $optionValue[$key]['type'] = ($type === 'uf' ? 'Profile' : ucfirst($type));

      if ($type == 'native') {
        $nativeDetails = CRM_Cdashtabs_Settings::getUserDashboardOptionsDetails($optionId);
        $optionValue[$key]['sectionId'] = $nativeDetails['sectionId'];

        if (!in_array($optionId, $nativeUserDashboardOptions)) {
          unset($optionValue[$key]);
        }
      }
      else {
        // Remove ufgroup option value in section list if is_cdash is null
        $cdashtabsOptionValue = json_decode(CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $key, 'value'));
        if (!$cdashtabsOptionValue->is_cdash) {
          unset($optionValue[$key]);
          continue;
        }

        $optionValue[$key]['sectionId'] = $optionId;
        $optionValue[$key]['label'] = CRM_Cdashtabs_Settings::getProfileTitle($optionId);

        // Remove from section if didn't exist in ufgroup profile
        // since we can't remove it using cdashtabs_civicrm_post hook
        $uFGroup = \Civi\Api4\UFGroup::get()
          ->addWhere('id', '=', $optionId)
          ->addOrderBy('id', 'DESC')
          ->execute()
          ->first();
        if (!$uFGroup) {
          unset($optionValue[$key]);
        }
      }
    }

    $this->assign('action', $action);
    $this->assign('rows', $optionValue);

    if ($action & (CRM_Core_Action::UPDATE | CRM_Core_Action::ADD)) {
      $this->edit($action);
    }

    parent::run();
  }

  /**
   * Get name of edit form.
   *
   *   Classname of edit form.
   */
  public function edit($action) {
    // create a simple controller for editing custom data
    $controller = new CRM_Core_Controller_Simple('CRM_Cdashtabs_Form_Section', ts('Contact Dashboard Tabs: Sections'), $action);

    // set the userContext stack
    $session = CRM_Core_Session::singleton();
    $session->pushUserContext(CRM_Utils_System::url('civicrm/admin/cdashtabs/section/', 'reset=1'));
    $controller->setEmbedded(TRUE);
    $controller->process();
    $controller->run();
  }

}
