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
   */
  function __construct($provider = [], $skipAuth = FALSE) {

    // Initialize vars.
    $this->_apiType = CRM_Utils_Array::value('api_type', $provider, 'http');
    $this->_providerInfo = $provider;

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
   * @throws \CiviCRM_API3_Exception
   * @access public
   * @throws \CiviCRM_API3_Exception
   */
  function send($recipients, $header, $message, $jobID = NULL, $userID = NULL) {

    if ($this->_apiType == 'http') {

      // STEP 1. Send SMS.
      // ******
      $delivery = [];
      $delivery['ApiKey'] = $this->_providerInfo['password'];
      $delivery['to'] = $header['To'];
      $delivery['message'] = $message;

      // Send to Proximus API via curl.
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->_providerInfo['api_url'] . '/Message');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($delivery));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $proximus = curl_exec($ch);
      curl_close($ch);

      // Convert JSON to object.
      $proximus = json_decode($proximus);

      // STEP 2. Build response array and create SMS delivery activity.
      // ******
      $response = [];
      $response['contact_id'] = $header['contact_id'];

      // Check if 'outbound' or 'mass' mailing.
      if (isset($header['parent_activity_id'])) {
        $response['type'] = "outbound";
        $parent = $header['parent_activity_id'];
        $subject = $header['activity_subject'];
      }
      else {
        $response['type'] = "mass";
        $parent = NULL;
        $subject = 'Delivery Mass SMS';
      }

      // Append proximus results.
      $response['proximus'] = $proximus;

      // Check status.
      $status = 'Unreachable';
      if ($proximus->ResultCode == 0 && $proximus->ResultDescription == 'Success') {
        $status = 'Completed';
      }

      // Create SMS delivery activity.
      $activity = civicrm_api3('Activity', 'create', [
        'sequential' => 1,
        'activity_type_id' => "SMS delivery",
        'parent_id' => $parent,
        'subject' => $subject,
        'details' => json_encode($response),
        'source_contact_id' => "user_contact_id",
        "target_id" => $header['contact_id'],
        'status_id' => $status,
      ]);

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

