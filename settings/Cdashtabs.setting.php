<?php

use CRM_Cdashtabs_ExtensionUtil as E;

return array(
  'cdashtabs_use_tabs' => array(
    'group_name' => 'Cdashtabs Use Tabs',
    'group' => 'cdashtabs',
    'name' => 'cdashtabs_use_tabs',
    'add' => '5.0',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => '',
    'title' => E::ts('Use Tabs on Dashboard?'),
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => 1,
    'html_type' => 'radio',
  ),
);
