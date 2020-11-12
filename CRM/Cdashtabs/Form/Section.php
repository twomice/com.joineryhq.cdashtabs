<?php

use CRM_Cdashtabs_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Cdashtabs_Form_Section extends CRM_Core_Form {

  /**
   * Pre-process
   */
  public function preProcess() {
    $this->_gName = 'cdashtabs';
    $this->_gid = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup',
      $this->_gName,
      'id',
      'name'
    );
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive',
      $this, FALSE, 0
    );
    $this->_action = CRM_Utils_Request::retrieve('action', 'String',
      $this, FALSE, 'browse'
    );
  }

  /**
   * Set default values for the form.
   */
  public function setDefaultValues() {
    $defaults = parent::setDefaultValues();

    $defaultValues = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $this->_id, 'value');

    foreach (json_decode($defaultValues) as $key => $value) {
      $defaults[$key] = !empty($value) ? 1 : 0;
    }

    $defaults['value'] = $defaultValues;
    $defaults['label'] = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $this->_id, 'label');
    $defaults['weight'] = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $this->_id, 'weight');

    return $defaults;
  }

  public function buildQuickForm() {
    $this->add('text',
      'label',
      ts('Label'),
      CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'label'),
      TRUE
    );
    $this->addRule('label',
      ts('This Label already exists in the database for this option group. Please select a different Label.'),
      'optionExists',
      ['CRM_Core_DAO_OptionValue', $this->_id, $this->_gid, 'label', FALSE]
    );

    $this->add('hidden',
      'value',
      ts('Value'),
      CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'value'),
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
    $params = $this->exportValues();
    $values = json_decode($params['value']);

    if ($values->is_cdash !== $params['is_cdash']) {
      $values->is_cdash = $params['is_cdash'];
    }

    if ($values->is_show_pre_post !== $params['is_show_pre_post']) {
      $values->is_show_pre_post = $params['is_show_pre_post'];
    }

    $saveOptionValue = [
      'label' => $params['label'],
      'value' => json_encode($values),
      'description' => '',
      'weight' => $params['weight'],
      'is_active' => '1',
      'filter' => CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $this->_id, 'filter', 'id'),
      'option_group_id' => $this->_gid,
      'id' => $this->_id,
    ];

    $optionValue = CRM_Core_OptionValue::addOptionValue($saveOptionValue, $this->_gName, $this->_action, $this->_id);

    CRM_Core_Session::setStatus(ts('The %1 \'%2\' has been saved.', [
      1 => 'Contact Dashboard Tabs: Section',
      2 => $optionValue->label,
    ]), ts('Saved'), 'success');

    $this->ajaxResponse['optionValue'] = $optionValue->toArray();

    parent::postProcess();
  }

}
