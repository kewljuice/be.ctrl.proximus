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
   * @var	string
   */
  protected $_apiType = 'http';

  /**
   * provider details
   * @var	string
   */
  protected $_providerInfo = array();

  /**
   * Temporary file resource id
   * @var	resource
   */
  protected $_fp;

  public $_apiURL = "https://api.ringring.be/sms/V1";

  protected $_messageType = array();

  protected $_messageStatus = array();

  /**
   * We only need one instance of this object. So we use the singleton
   * pattern and cache the instance in this variable
   *
   * @var object
   * @static
   */
  static private $_singleton = array();

  /**
   * Constructor
   *
   * Create and auth a proximus session.
   *
   * @param array $provider
   * @param bool $skipAuth
   *
   * @return void
   */
  function __construct($provider = array( ), $skipAuth = FALSE) {

    // Log.
    watchdog("be_ctrl_proximus", "construct" . $skipAuth);

    // Initialize vars.
    $this->_apiType = CRM_Utils_Array::value('api_type', $provider, 'http');
    $this->_providerInfo = $provider;

    // Authenticate.
    $this->authenticate();
  }

  /**
   * singleton function used to manage this object
   *
   * @param array $providerParams
   * @param bool $force
   * @return object
   * @static
   */
  static function &singleton($providerParams = array(), $force = FALSE) {
    $providerID = CRM_Utils_Array::value('provider_id', $providerParams);
    $skipAuth   = $providerID ? FALSE : TRUE;
    $cacheKey   = (int) $providerID;
    if (!isset(self::$_singleton[$cacheKey]) || $force) {
      $provider = array();
      if ($providerID) {
        $provider = CRM_SMS_BAO_Provider::getProviderInfo($providerID, 'name');
      }
      self::$_singleton[$cacheKey] = new be_ctrl_proximus($provider, $skipAuth);
    }
    return self::$_singleton[$cacheKey];
  }

  /**
   * Authenticate to the SMS Server.
   * Not needed with Proximus
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
   * @internal param \the $array message with a to/from/text
   *
   * @return mixed true on sucess or PEAR_Error object
   * @access public
   */
  function send($recipients, $header, $message, $jobID = NULL, $userID = NULL) {
    if ($this->_apiType == 'http') {

      // log
      watchdog("be_ctrl_proximus", "send");

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

