<?php
use CRM_Cdashtabs_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Cdashtabs_Upgrader extends CRM_Cdashtabs_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   */
  public function install() {
    $results = \Civi\Api4\OptionGroup::create()
      ->setCheckPermissions(FALSE)
      ->addValue('name', 'cdashtabs')
      ->addValue('title', 'Contact Dadshboard Tabs Extension Options')
      ->addValue('is_active', TRUE)
      ->addValue('is_locked', TRUE)
      ->addValue('is_reserved', TRUE)
      ->execute();

    $nativeUserDashboardOptions = \Civi\Api4\OptionValue::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('option_group_id:name', '=', 'user_dashboard_options')
      ->addWhere('value', '!=', 10)
      ->addOrderBy('weight', 'ASC')
      ->execute();

    foreach ($nativeUserDashboardOptions as $option) {
      // Copy user dashboard options to cdashtabs for tab order
      $newCdashtabsOption = \Civi\Api4\OptionValue::create()
        ->setCheckPermissions(FALSE)
        ->addValue('option_group_id:name', 'cdashtabs')
        ->addValue('label', $option['label'])
        ->addValue('value', $option['value'])
        ->addValue('name', "native_{$option['value']}")
        ->addValue('filter', $option['filter'])
        ->addValue('weight', $option['weight'])
        ->addValue('is_optgroup', $option['is_optgroup'])
        ->addValue('is_reserved', $option['is_reserved'])
        ->addValue('is_active', $option['is_active'])
        ->execute();
    }
  }

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   */
  // public function postInstall() {
  //  $customFieldId = civicrm_api3('CustomField', 'getvalue', array(
  //    'return' => array("id"),
  //    'name' => "customFieldCreatedViaManagedHook",
  //  ));
  //  civicrm_api3('Setting', 'create', array(
  //    'myWeirdFieldSetting' => array('id' => $customFieldId, 'weirdness' => 1),
  //  ));
  // }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   */
  public function uninstall() {
    try {
      $optionGroups = \Civi\Api4\OptionGroup::get()
        ->setCheckPermissions(FALSE)
        ->addSelect('id')
        ->addWhere('name', '=', 'cdashtabs')
        ->setLimit(25)
        ->execute();
      foreach ($optionGroups as $optionGroup) {
        $results = \Civi\Api4\OptionGroup::delete()
          ->setCheckPermissions(FALSE)
          ->addWhere('id', '=', $optionGroup['id'])
          ->execute();
      }
    }
    catch (API_Exception $e) {
    }
  }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  // public function enable() {
  //  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  // public function disable() {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4200() {
  //   $this->ctx->log->info('Applying update 4200');
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
  //   CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
  //   return TRUE;
  // }


  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4201() {
  //   $this->ctx->log->info('Applying update 4201');
  //   // this path is relative to the extension base dir
  //   $this->executeSqlFile('sql/upgrade_4201.sql');
  //   return TRUE;
  // }


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4202() {
  //   $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

  //   $this->addTask(E::ts('Process first step'), 'processPart1', $arg1, $arg2);
  //   $this->addTask(E::ts('Process second step'), 'processPart2', $arg3, $arg4);
  //   $this->addTask(E::ts('Process second step'), 'processPart3', $arg5);
  //   return TRUE;
  // }
  // public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  // public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  // public function processPart3($arg5) { sleep(10); return TRUE; }

  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
   */
  // public function upgrade_4203() {
  //   $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

  //   $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
  //   $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
  //   for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
  //     $endId = $startId + self::BATCH_SIZE - 1;
  //     $title = E::ts('Upgrade Batch (%1 => %2)', array(
  //       1 => $startId,
  //       2 => $endId,
  //     ));
  //     $sql = '
  //       UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
  //       WHERE id BETWEEN %1 and %2
  //     ';
  //     $params = array(
  //       1 => array($startId, 'Integer'),
  //       2 => array($endId, 'Integer'),
  //     );
  //     $this->addTask($title, 'executeSql', $sql, $params);
  //   }
  //   return TRUE;
  // }

}
