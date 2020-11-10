<?php

use CRM_Cdashtabs_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Cdashtabs_Form_CDashTabs extends CRM_Core_Form {

  /**
   * Set default values for the form.
   */
  public function setDefaultValues() {
    $defaults = parent::setDefaultValues();

    $gid = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup',
      'cdashtabs',
      'id',
      'name'
    );

    $id = CRM_Utils_Request::retrieve('id', 'Positive',
      $this, FALSE, 0
    );

    $defaultValues = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $id, 'value');

    foreach (json_decode($defaultValues) as $key => $value) {
      $defaults[$key] = !empty($value) ? 1 : 0;
    }

    $defaults['label'] = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $id, 'label');
    $defaults['weight'] = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $id, 'weight');

    return $defaults;
  }

  public function buildQuickForm() {

    $this->add('text',
      'label',
      ts('Label'),
      CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'label'),
      TRUE
    );

    $this->add('checkbox',
      'is_cdash',
      ts('Display on Contact Dashboard?')
    );

    $this->add('checkbox',
      'is_show_pre_post',
      ts('Display pre- and post-help on Contact Dashboard?')
    );

    $this->add('number',
      'weight',
      ts('Order'),
      CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'weight'),
      TRUE
    );

    $this->addRule('weight', ts('is a numeric field'), 'numeric');

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ),
      array(
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ),
    ));

    parent::buildQuickForm();
  }

  public function postProcess() {
    parent::postProcess();
  }

}
