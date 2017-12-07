<?php

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 * $Id$
 *
 */
class be_ctrl_proximus extends CRM_SMS_Provider {

  /**
   * api type to use to send a message
   *
   * @var  string
   */
  protected $_apiType = 'http';

  /**
   * provider details
   *
   * @var  string
   */
  protected $_providerInfo = [];

  /**
   * Temporary file resource id
   *
   * @var  resource
   */
  protected $_fp;

  public $_apiURL = 'https://api.ringring.be/sms/V1';

  protected $_messageType = [];

  protected $_messageStatus = [];

  /**
   * Activity "SMS delivery" id.
   */
  protected $_smsDelivery = '';

  /**
   * We only need one instance of this object. So we use the singleton
   * pattern and cache the instance in this variable
   *
   * @var object
   * @static
   */
  static private $_singleton = [];

  /**
   * Constructor
   *
   * Create and auth a Proximus session.
   *
   * @param array $provider
   * @param bool $skipAuth
   *
   * @throws \CiviCRM_API3_Exception
   */
  function __construct($provider = [], $skipAuth = FALSE) {

    // Initialize vars.
    $this->_apiType = CRM_Utils_Array::value('api_type', $provider, 'http');
    $this->_providerInfo = $provider;

    // Fetch "SMS delivery" id.
    $this->_smsDelivery = civicrm_api3('OptionValue', 'getvalue', [
      'sequential' => 1,
      'return' => "value",
      'name' => "SMS delivery",
      'option_group_id' => "activity_type",
    ]);

    // Authenticate.
    $this->authenticate();
  }

  /**
   * Singleton function used to manage this object.
   *
   * @param array $providerParams
   * @param bool $force
   *
   * @return object
   * @static
   */
  static function &singleton($providerParams = [], $force = FALSE) {
    $providerID = CRM_Utils_Array::value('provider_id', $providerParams);
    $skipAuth = $providerID ? FALSE : TRUE;
    $cacheKey = (int) $providerID;
    if (!isset(self::$_singleton[$cacheKey]) || $force) {
      $provider = [];
      if ($providerID) {
        $provider = CRM_SMS_BAO_Provider::getProviderInfo($providerID);
      }
      self::$_singleton[$cacheKey] = new be_ctrl_proximus($provider, $skipAuth);
    }
    return self::$_singleton[$cacheKey];
  }

  /**
   * Authenticate to the SMS Server.
   * Not needed with Proximus.
   *
   * @return boolean TRUE
   * @access public
   * @since 1.1
   */
  function authenticate() {
    return TRUE;
  }

  /**
   * Send an SMS Message via the Proximus API Server
   *
   * @param $recipients
   * @param $header
   * @param $message
   * @param null $jobID
   * @param null $userID
   *
   * @return mixed true on success or PEAR_Error object
   * @access public
   * @throws \CRM_Core_Exception
   */
  function send($recipients, $header, $message, $jobID = NULL, $userID = NULL) {

    if ($this->_apiType == 'http') {

      // STEP 1. Send SMS.
      // ******
      $delivery = [];
      $delivery['url'] = $this->_providerInfo['api_url'] . '/Message';
      $delivery['key'] = $this->_providerInfo['password'];
      $delivery['to'] = $header['To'];
      $delivery['msg'] = $message;

      // Log delivery.
      watchdog("be_ctrl_proximus", "step1: delivery:" . print_r($delivery, TRUE));

      // STEP 2. Build response array and create SMS delivery activity.
      // ******
      $response = [];
      $response['contact_id'] = $header['contact_id'];
      $response['provider_id'] = $header['provider_id'];

      // Check if 'outbound' or 'mass' mailing.
      if (isset($header['parent_activity_id'])) {
        $response['type'] = "outbound";
        $response['parent_activity_id'] = $header['parent_activity_id'];
      }
      else {
        $response['type'] = "mass";
      }

      // Log delivery response.
      watchdog("be_ctrl_proximus", "step2: delivery response:" . print_r($response, TRUE));

      // TODO Create SMS delivery activity (aid:44)!
      // watchdog("be_ctrl_proximus", "step2: activity id " . print_r($this->_smsDelivery, TRUE));

      // $msgID = 'ID:' . rand();
      // $activity = $this->createActivity($msgID, $message, $header, $jobID, $userID);
      //watchdog("be_ctrl_proximus", "send msgID:" . print_r($msgID, TRUE));
      //watchdog("be_ctrl_proximus", "send activity:" . print_r($activity, TRUE));

    }
    return TRUE;
  }

  /**
   * @return bool
   */
  function callback() {
    return FALSE;
  }

  /**
   * @return $this|null|object
   */
  function inbound() {
    return NULL;
  }

}

