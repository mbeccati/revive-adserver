<?php

/*
+---------------------------------------------------------------------------+
| Revive Adserver                                                           |
| http://www.revive-adserver.com                                            |
|                                                                           |
| Copyright: See the COPYRIGHT.txt file.                                    |
| License: GPLv2 or later, see the LICENSE.txt file.                        |
+---------------------------------------------------------------------------+
*/

require_once MAX_PATH . '/lib/max/Delivery/common.php';

/**
 * A class for testing the common.php functions.
 *
 * @package    MaxDelivery
 * @subpackage TestSuite
 */
class Test_DeliveryCommon extends UnitTestCase
{
    /** @var int */
    var $original_server_port;

    /**
     * The constructor method.
     */
    function __construct()
    {
        parent::__construct();
    }

    function setUp()
    {
        $this->original_server_port = $_SERVER['SERVER_PORT'] ?? null;
        $GLOBALS['_MAX']['CONF']['webpath']['delivery']     = 'www.maxstore.net/www/delivery';
        $GLOBALS['_MAX']['CONF']['webpath']['deliverySSL']  = 'secure.maxstore.net/www/delivery';

        $GLOBALS['_MAX']['CONF']['delivery']['secret'] = base64_encode(hash('sha256', 'revive-adserver'));
    }

    function tearDown()
    {
        if ($this->original_server_port) {
            $_SERVER['SERVER_PORT'] = $this->original_server_port;
        }
    }

    function test_MAX_commonGetDeliveryUrl_Uses_HTTP_Scheme_For_Nonsecure_URLs()
    {
        $_SERVER['SERVER_PORT'] = 80;
        $GLOBALS['_MAX']['CONF']['openads']['sslPort']  = 443;
        $url = MAX_commonGetDeliveryUrl('test.html');
        $this->assertEqual($url, 'http://www.maxstore.net/www/delivery/test.html', "HTTP delivery on standard, non-secure ports only need to generate plain HTTP URLs for delivery, with no explicit port.");
    }

    function test_MAX_commonGetDeliveryUrl_Uses_HTTPS_Scheme_For_Secure_URLs()
    {
        $_SERVER['SERVER_PORT'] = 443;
        $GLOBALS['_MAX']['SSL_REQUEST'] = true;
        $GLOBALS['_MAX']['CONF']['openads']['sslPort']  = 443;
        $url    = MAX_commonGetDeliveryUrl('test.html');
        $this->assertEqual($url, 'https://secure.maxstore.net/www/delivery/test.html', "Delivery requests that come in on the 'secure port' should use HTTPS URLs. %s");
        unset($GLOBALS['_MAX']['SSL_REQUEST']);
    }

    function test_MAX_commonGetDeliveryUrl_Includes_Nonstandard_Secure_Port_Number()
    {
        $_SERVER['SERVER_PORT'] = 4430;
        $GLOBALS['_MAX']['SSL_REQUEST'] = true;
        $GLOBALS['_MAX']['CONF']['openads']['sslPort']  = 4430;
        $url    = MAX_commonGetDeliveryUrl('test.html');
        $this->assertEqual($url, 'https://secure.maxstore.net:4430/www/delivery/test.html', "A non-standard port number should be explicitly provided in delivery URLs. %s");
        unset($_SERVER['HTTPS']);
    }

	/**
	 * Test1 return a URL
	 * A function to generate the URL for delivery scripts.
	 *
	 * @param string $file The file name of the delivery script.
	 * @return string The URL to the delivery script.
	 */
	function test_MAX_commonConstructDeliveryUrl()
	{
		$this->sendMessage('test_MAX_commonConstructDeliveryUrl');
        $GLOBALS['_MAX']['CONF']['webpath']['delivery'] 	= 'www.maxstore.net/www/delivery';
		$file 	= 'test.html';
		$ret 	= MAX_commonConstructDeliveryUrl($file);
        $this->assertEqual($ret, 'http://www.maxstore.net/www/delivery/test.html');
	}

	/**
	 * Test1 return secure URL
	 * A function to generate the secure URL for delivery scripts.
	 *
	 * @param string $file The file name of the delivery script.
	 * @return string The URL to the delivery script.
	 */
	function test_MAX_commonConstructSecureDeliveryUrl()
	{
		$this->sendMessage('test_MAX_commonConstructSecureDeliveryUrl');
        $GLOBALS['_MAX']['CONF']['webpath']['deliverySSL'] 	= 'secure.maxstore.net/www/delivery';
        $GLOBALS['_MAX']['CONF']['openads']['sslPort'] 			= 444;
		$file = 'test.html';
		$ret 	= MAX_commonConstructSecureDeliveryUrl($file);
        $this->assertEqual($ret, 'https://secure.maxstore.net:444/www/delivery/test.html');
	}

	/**
	 * Test1: return unsecure URL
	 * Test2: return secure URL
	 * A function to generate the URL for delivery scripts without a protocol.
	 *
	 * @param string $file The file name of the delivery script.
	 * @param boolean $ssl Use the SSL delivery path (true) or not. Default is false.
	 * @return string The parital URL to the delivery script (i.e. without
	 *                an 'http:' or 'https:' prefix).
	 */
	function test_MAX_commonConstructPartialDeliveryUrl()
	{
		$this->sendMessage('test_MAX_commonConstructPartialDeliveryUrl');
        $GLOBALS['_MAX']['CONF']['webpath']['deliverySSL'] 	= 'secure.maxstore.net/www/delivery';
        $GLOBALS['_MAX']['CONF']['webpath']['delivery'] 	= 'www.maxstore.net/www/delivery';
		$file = 'test.html';
		// Test1
		$ret = MAX_commonConstructPartialDeliveryUrl($file, false);
        $this->assertEqual($ret, '//www.maxstore.net/www/delivery/test.html');
        // Test2
		$ret = MAX_commonConstructPartialDeliveryUrl($file, true);
        $this->assertEqual($ret, '//secure.maxstore.net/www/delivery/test.html');
	}

	/**
	 * Remove an assortment of special characters from a variable or array:
	 * 1.  Strip slashes if magic quotes are turned on.
	 * 2.  Strip out any HTML
	 * 3.  Strip out any CRLF
	 * 4.  Remove any white space
	 *
	 * @access  public
	 * @param   string $var  The variable to process.
	 * @return  string       $var, minus any special quotes.
	 */
	function test_MAX_commonRemoveSpecialChars()
	{
		$this->sendMessage('test_MAX_commonRemoveSpecialChars');
        $strIn0	= "Mr O'Reilly<br />".chr(13).chr(10);
        $strIn1	= "'Mr Reilly'\r\n";

		$strRe0 = "Mr O'Reilly";
		$strRe1 = "'Mr Reilly'";
		$aIn	= array(0 => $strIn0,
						1 => array(0 => $strIn1),
						);
		MAX_commonRemoveSpecialChars($aIn);
		$prn    = var_export($aIn[1][0], true);

        $this->assertEqual($aIn[0], $strRe0);
        $this->assertEqual($aIn[1][0], $strRe1,'test_MAX_commonRemoveSpecialChars');
	}

	/**
	 * TODO: implement as web test
	 * This function sends the anti-caching headers when called
	 *
	 */
	function test_MAX_commonSetNoCacheHeaders()
	{
		$this->sendMessage('test_MAX_commonSetNoCacheHeaders: Not Implemented');
	}

	/**
	 *
	 * This function takes an array of variable names
	 * and makes them available in the global scope
	 *
	 * $_POST values take precedence over $_GET values
	 *
	 */
	function test_MAX_commonRegisterGlobalsArray()
	{
	    $tmpGlobals = $GLOBALS;
	    $_GET['max_test_get']      = '0';
	    $_POST['max_test_get']     = '1';
	    $_GET['max_test_post']     = '0';
	    $_POST['max_test_post']    = '1';
		MAX_commonRegisterGlobalsArray(array('max_test_get', 'max_test_post'));
		$this->assertTrue(array_key_exists('max_test_get', $GLOBALS),'max_test_get exists');
		$this->assertTrue(array_key_exists('max_test_post', $GLOBALS),'max_test_post exists');
		$this->assertTrue($GLOBALS['max_test_get'],'GLOBALS precedence error');
		$this->assertTrue($GLOBALS['max_test_post'],'GLOBALS precedence error');
		$GLOBALS = $tmpGlobals;
	}

	/**
	 * Test1: return urldecoded/decrypted string
	 * Test2: return urldecoded string
	 *
	 * This function takes the "source" value and normalises it
	 * and encrypts it if necessary
	 *
	 * @param string The value from the source parameter
	 * @return string Encrypted source
	 */
	function test_MAX_commonDeriveSource()
	{
        $str = "http://www.somewhere.com/index.php?a=this is a file&b=43589uhhjkh";
	    // Test1: obfuscate (encrypt) and return
	    $source = urlencode($str);
		$GLOBALS['_MAX']['CONF']['delivery']['obfuscate'] = 1;
		$expect = '{obfs:220208208212266277277205205205278209213215223205220223210223278225213215277219214224223204278212220212261227263208220219209292219209292227292222219216223286226263272273271268267207220220218217220}';
        $return = MAX_commonDeriveSource($source);
        $this->assertEqual($return, $expect);
        // Test2: urldecode and return
	    $source = urlencode($str);
		$GLOBALS['_MAX']['CONF']['delivery']['obfuscate'] = 0;
		$expect = $str;
        $return = MAX_commonDeriveSource($source);
        $this->assertEqual($return, $expect);
	}

    /**
     * Test the _MAX_commonEncrypt and _MAX_commonDecrypt functions.
     */
	function test_MAX_commonEncryptDecrypt()
	{
		$this->sendMessage('test_MAX_commonEncryptDecrypt');
		$string = 'Lorem ipsum dolor sit amet ipso facto';
		$GLOBALS['_MAX']['CONF']['delivery']['obfuscate'] = 1;
		$retEnc = MAX_commonEncrypt($string);
        $this->assertNotEqual($retEnc, $string);
		$retDec = MAX_commonDecrypt($retEnc);
        $this->assertNotEqual($retDec, $retEnc);
        $this->assertEqual($retDec, $string);

		$GLOBALS['_MAX']['CONF']['delivery']['obfuscate'] = 0;
		$retDec = MAX_commonDecrypt($string);
        $this->assertEqual($retDec, $string);
	}

    function test_MAX_commonInitVariables()
    {
        // Test 1 : common defaults
		$GLOBALS['_MAX']['CONF']['delivery']['obfuscate'] = 1;

        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;

        MAX_commonInitVariables();

        $this->assertEqual($context, array(), '$context');
        $this->assertEqual($source, '{obfs:}', '$source'); // only if conf->obfuscate
        $this->assertEqual($target, '', '$target');
        $this->assertEqual($withText, '', '$withText');
        $this->assertEqual($withtext, 0, '$withtext');
        $this->assertEqual($withtext, $withText, '$withtext/$withText');
        $this->assertEqual($ct0, '', '$ct0');
        $this->assertEqual($what, '', '$what');
        $this->assertTrue(in_array($loc, array(stripslashes($loc), $_SERVER['HTTP_REFERER'] ?? '','')));
        $this->assertEqual($referer, null, '$referer');
        $this->assertEqual($zoneid, null, '$zoneid');
        $this->assertEqual($campaignid, null, '$campaignid');
        $this->assertEqual($bannerid, null, '$bannerid');

        $GLOBALS['_MAX']['CONF']['delivery']['obfuscate'] = 0;

        // Test 2 : non-numeric id fields should be emptied
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;

        $zoneid     = '1 and (select * from users)';
        $campaignid = '1 and (select * from users)';
        $bannerid   = '1 and (select * from users)';
        $clientid   = '1 and (select * from users)';

        MAX_commonInitVariables();

        $this->assertEqual($GLOBALS['zoneid']      , '');
        $this->assertEqual($GLOBALS['campaignid']  , '');
        $this->assertEqual($GLOBALS['clientid']    , '');
        $this->assertEqual($GLOBALS['bannerid']    , '');
        $this->assertEqual($GLOBALS['what']        , '');

        // Test 3 : "what" field should get value from id field
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $bannerid   = '456';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['what']    , 'bannerid:456');

        // Test 4 : id fields should get value from "what" field
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $what = 'bannerid:456';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['what']    , 'bannerid:456');
        $this->assertEqual($GLOBALS['bannerid'], '456');

        // Test 5 : bad what field should leave empty what and id field
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;

        $what = 'bannerid:1 and (select * from users)';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['what']    , 'bannerid:1 and (select * from users)');
        $this->assertEqual($GLOBALS['bannerid'], '');

        // Test 6 : target and charset fields should contain no whitespace
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $target  = '';
        $charset = '';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['target'], '');
        $this->assertEqual($GLOBALS['charset'], 'UTF-8');

        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $target  = 'select * from users';
        $charset = 'select * from users';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['target'], '');
        $this->assertEqual($GLOBALS['charset'], 'UTF-8');

        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $target  = '_self';
        $charset = 'LATIN-1';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['target'], '_self');
        $this->assertEqual($GLOBALS['charset'], 'LATIN-1');

        // Test 7 : withtext and withText fields (numeric 0 or 1)
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $withText  = 1;
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['withText'], 1);
        $this->assertEqual($GLOBALS['withtext'], 1);

        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $withtext  = 1;
        MAX_commonInitVariables();
        $this->assertFalse(isset($GLOBALS['withText']));
        $this->assertEqual($GLOBALS['withtext'], 1);

        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        $withtext  = 'select * from users';
        MAX_commonInitVariables();
        $this->assertEqual($GLOBALS['withtext'], 0);

        // Test 8 : URLs...
        $this->_unsetMAXGlobals();
        global $context, $source, $target, $withText, $withtext, $ct0, $what, $loc, $referer, $zoneid, $campaignid, $bannerid, $clientid, $charset;
        //$ct0    = '';
        //$loc    = '';
        //$referer = '';
    }

    function _unsetMAXGlobals()
    {
        unset($GLOBALS['zoneid']);
        unset($GLOBALS['campaignid']);
        unset($GLOBALS['clientid']);
        unset($GLOBALS['bannerid']);
        unset($GLOBALS['what']);
        unset($GLOBALS['target']);
        unset($GLOBALS['charset']);
        unset($GLOBALS['withText']);
        unset($GLOBALS['withtext']);

        MAX_commonRegisterGlobalsArray(array('context', 'source', 'target', 'withText', 'withtext', 'ct0', 'what', 'loc', 'referer', 'zoneid', 'campaignid', 'bannerid', 'clientid', 'charset'));
    }


    /**
     * @todo create this as web test as it sends headers
     *
     */
    function test_MAX_commonDisplay1x1()
    {
        $this->assertTrue(true);
    }

    function test_OX_Delivery_Common_checkClickSignature_noTimestamp()
    {
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(0, 0, ''));

        // Valid destination and signature
        $_REQUEST['sig'] = OX_Delivery_Common_getClickSignature(1, 1, 'http://example.com/');
        $this->assertTrue(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/'));

        // Tamper with IDs or destination
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/foo.html'));
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 2, 'http://example.com/'));
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(2, 1, 'http://example.com/'));
    }

    function test_OX_Delivery_Common_checkClickSignature_withTimestamp()
    {
        // Set Now
        $GLOBALS['_MAX']['NOW'] = gmmktime(10, 50, 0, 2, 25, 2021);

        // Sign with no URL, hence using the current time
        $_REQUEST['ts'] = (string) MAX_commonGetTimeNow();
        $_REQUEST['sig'] = OX_Delivery_Common_getClickSignature(1, 1, $_REQUEST['ts']);

        // Advance the wall clock
        $GLOBALS['_MAX']['NOW'] += 30;

        // Zero validity
        $GLOBALS['_MAX']['CONF']['delivery']['clickUrlValidity'] = 0;
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/'));

        // Smaller validity
        $GLOBALS['_MAX']['CONF']['delivery']['clickUrlValidity'] = 15;
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/'));

        // Bigger validity
        $GLOBALS['_MAX']['CONF']['delivery']['clickUrlValidity'] = 60;
        $this->assertTrue(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/foo.html'));

        // Exact validity, any dest is ok
        $GLOBALS['_MAX']['CONF']['delivery']['clickUrlValidity'] = 30;
        $this->assertTrue(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/'));
        $this->assertTrue(OX_Delivery_Common_checkClickSignature(1, 1, 'https://example.com/'));
        $this->assertTrue(OX_Delivery_Common_checkClickSignature(1, 1, 'https://example.com/foo.html'));

        // Unless no destitaion, protocol is not http(s) or IDs have been tampered with
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 1, ''));
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 1, 'ftp://example.com/'));
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 2, 'http://example.com/'));
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(2, 1, 'http://example.com/'));

        // Or maybe the timestamp
        $_REQUEST['ts'] = $GLOBALS['_MAX']['NOW'] - 15;
        $this->assertFalse(OX_Delivery_Common_checkClickSignature(1, 1, 'http://example.com/foobar.html'));

        // Cleanup
        unset($GLOBALS['_MAX']['NOW']);
    }

}

?>
