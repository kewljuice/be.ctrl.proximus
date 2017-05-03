<?php

require_once 'proximus.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function proximus_civicrm_config(&$config) {
  _proximus_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function proximus_civicrm_xmlMenu(&$files) {
  _proximus_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function proximus_civicrm_install() {

  // Add "Proximus" SMS provider.
  $groupID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup', 'sms_provider_name', 'id', 'name');
  $params  =
    [
      'option_group_id' => $groupID,
      'label'           => 'Proximus',
      'value'           => 'be.ctrl.proximus',
      'name'            => 'proximus',
      'is_default'      => 1,
      'is_active'       => 1,
      'version'         => 3,
    ];
  require_once 'api/api.php';
  civicrm_api('option_value', 'create', $params);

  _proximus_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function proximus_civicrm_postInstall() {
  _proximus_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function proximus_civicrm_uninstall() {

  // Remove "Proximus" SMS provider.
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', 'proximus', 'id', 'name');
  if ($optionID) {
    CRM_Core_BAO_OptionValue::del($optionID);
  }

  $filter    = ['name' => 'be.ctrl.proximus'];
  $Providers = CRM_SMS_BAO_Provider::getProviders(FALSE, $filter, FALSE);
  if ($Providers) {
    foreach ($Providers as $key => $value) {
      CRM_SMS_BAO_Provider::del($value['id']);
    }
  }

  _proximus_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function proximus_civicrm_enable() {

  // Enable "Proximus" SMS providers
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', 'proximus', 'id', 'name');
  if ($optionID) {
    CRM_Core_BAO_OptionValue::setIsActive($optionID, TRUE);
  }

  $filter    = ['name' => 'be.ctrl.proximus'];
  $Providers = CRM_SMS_BAO_Provider::getProviders(FALSE, $filter, FALSE);
  if ($Providers) {
    foreach ($Providers as $key => $value) {
      CRM_SMS_BAO_Provider::setIsActive($value['id'], TRUE);
    }
  }

  _proximus_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function proximus_civicrm_disable() {

  // Disable "Proximus" SMS providers
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', 'proximus', 'id', 'name');
  if ($optionID) {
    CRM_Core_BAO_OptionValue::setIsActive($optionID, FALSE);
  }

  $filter    = ['name' => 'be.ctrl.proximus'];
  $Providers = CRM_SMS_BAO_Provider::getProviders(FALSE, $filter, FALSE);
  if ($Providers) {
    foreach ($Providers as $key => $value) {
      CRM_SMS_BAO_Provider::setIsActive($value['id'], FALSE);
    }
  }

  _proximus_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function proximus_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _proximus_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function proximus_civicrm_managed(&$entities) {
  _proximus_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function proximus_civicrm_caseTypes(&$caseTypes) {
  _proximus_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function proximus_civicrm_angularModules(&$angularModules) {
  _proximus_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function proximus_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _proximus_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
