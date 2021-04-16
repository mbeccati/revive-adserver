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

require_once MAX_PATH . '/lib/max/Plugin.php';
// Using multi-dirname so that the tests can run from either plugins or plugins_repo
require_once dirname(dirname(dirname(__FILE__))) . '/Geo/UsMetro.delivery.php';

Language_Loader::load();

/**
 * A class for testing the Plugins_DeliveryLimitations_Geo_UsMetro class.
 *
 * @package    OpenXPlugin
 * @subpackage TestSuite
 */
class Plugins_TestOfPlugins_DeliveryLimitations_Geo_UsMetro extends UnitTestCase
{
    function testCheckGeoDma()
    {
        // =~ and !~ - Single country
        $this->assertTrue(MAX_checkGeo_UsMetro('662',    '=~', array('metro_code' => '662')));
        $this->assertTrue(MAX_checkGeo_UsMetro('662',   '!~', array('metro_code' => '790')));

        // =~ and !~ - Multiple country
        $this->assertTrue(MAX_checkGeo_UsMetro('662,790', '=~', array('metro_code' => '662')));
        $this->assertTrue(MAX_checkGeo_UsMetro('662,790', '=~', array('metro_code' => '790')));
        $this->assertTrue(MAX_checkGeo_UsMetro('662,790', '!~', array('metro_code' => '100')));
        $this->assertFalse(MAX_checkGeo_UsMetro('662,790', '!~', array('metro_code' => '790')));
    }
}

?>
