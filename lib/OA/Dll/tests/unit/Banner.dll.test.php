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

require_once MAX_PATH . '/lib/OA/Dll/Advertiser.php';
require_once MAX_PATH . '/lib/OA/Dll/AdvertiserInfo.php';
require_once MAX_PATH . '/lib/OA/Dll/Campaign.php';
require_once MAX_PATH . '/lib/OA/Dll/CampaignInfo.php';
require_once MAX_PATH . '/lib/OA/Dll/Banner.php';
require_once MAX_PATH . '/lib/OA/Dll/BannerInfo.php';
require_once MAX_PATH . '/lib/OA/Dll/tests/util/DllUnitTestCase.php';

/**
 * A class for testing DLL Banner methods
 *
 * @package    OpenXDll
 * @subpackage TestSuite
 */


class OA_Dll_BannerTest extends DllUnitTestCase
{
    /**
     * @var int
     */
    var $agencyId;

    /**
     * Errors
     *
     */
    var $unknownIdError = 'Unknown bannerId Error';
    var $unknownFormatError = 'Unrecognized image file format';

    var $binaryGif;

    /**
     * The constructor method.
     */
    function __construct()
    {
        parent::__construct();
        Mock::generatePartial(
            'OA_Dll_Banner',
            'PartialMockOA_Dll_Banner',
            array('checkPermissions')
        );
        Mock::generatePartial(
            'OA_Dll_Campaign',
            'PartialMockOA_Dll_Campaign_BannerTest',
            array('checkPermissions')
        );
        Mock::generatePartial(
            'OA_Dll_Advertiser',
            'PartialMockOA_Dll_Advertiser_BannerTest',
            array('checkPermissions', 'getDefaultAgencyId')
        );

        $this->binaryGif = "GIF89a\001\0\001\0\200\0\0\377\377\377\0\0\0!\371\004\0\0\0\0\0,\0\0\0\0\001\0\001\0\0\002\002D\001\0;";
    }

    function setUp()
    {
        $this->agencyId = DataGenerator::generateOne('agency');
    }

    function tearDown()
    {
        DataGenerator::cleanUp();
    }

    /**
     * A method to test Add, Modify and Delete.
     */
    function testAddModifyDelete()
    {
        $GLOBALS['_MAX']['CONF']['store']['mode']   = 0;
        $GLOBALS['_MAX']['CONF']['store']['webDir'] = MAX_PATH . '/var';

        $dllAdvertiserPartialMock = new PartialMockOA_Dll_Advertiser_BannerTest($this);
        $dllCampaignPartialMock   = new PartialMockOA_Dll_Campaign_BannerTest($this);
        $dllBannerPartialMock     = new PartialMockOA_Dll_Banner($this);

        $dllAdvertiserPartialMock->setReturnValue('getDefaultAgencyId', $this->agencyId);
        $dllAdvertiserPartialMock->setReturnValue('checkPermissions', true);
        $dllAdvertiserPartialMock->expectCallCount('checkPermissions', 2);

        $dllCampaignPartialMock->setReturnValue('checkPermissions', true);
        $dllCampaignPartialMock->expectCallCount('checkPermissions', 1);

        $dllBannerPartialMock->setReturnValue('checkPermissions', true);
        $dllBannerPartialMock->expectCallCount('checkPermissions', 9);

        $oAdvertiserInfo = new OA_Dll_AdvertiserInfo();
        $oAdvertiserInfo->advertiserName = 'test Advertiser name';
        $oAdvertiserInfo->agencyId       = $this->agencyId;

        $this->assertTrue($dllAdvertiserPartialMock->modify($oAdvertiserInfo),
                          $dllAdvertiserPartialMock->getLastError());

        $oCampaignInfo = new OA_Dll_CampaignInfo();

        $oCampaignInfo->advertiserId = $oAdvertiserInfo->advertiserId;

        // Add
        $this->assertTrue($dllCampaignPartialMock->modify($oCampaignInfo),
                          $dllCampaignPartialMock->getLastError());

        $oBannerInfo = new OA_Dll_BannerInfo();
        $oBannerInfo->campaignId = $oCampaignInfo->campaignId;

        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo),
                          $dllBannerPartialMock->getLastError());

        // Modify
        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo),
                          $dllBannerPartialMock->getLastError());

        // Delete
        $this->assertTrue($dllBannerPartialMock->delete($oBannerInfo->bannerId),
                          $dllBannerPartialMock->getLastError());


        // Add gif (SQL stored)
        $oBannerInfo2 = new OA_Dll_BannerInfo();
        $oBannerInfo2->campaignId = $oCampaignInfo->campaignId;
        $oBannerInfo2->storageType = 'sql';
        $oBannerInfo2->aImage = array(
            'filename' => '1x1.gif',
            'content'  => $this->binaryGif
        );

        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo2),
                          $dllBannerPartialMock->getLastError());

        $doBanners = OA_Dal::staticGetDO('banners', $oBannerInfo2->bannerId);
        $this->assertEqual($doBanners->width, 1);
        $this->assertEqual($doBanners->height, 1);
        $this->assertEqual($doBanners->contenttype, 'gif');

        $doImages = OA_Dal::staticGetDO('images', $doBanners->filename);
        $this->assertEqual($doImages->contents, $this->binaryGif);

        // Add gif (Web stored)
        $oBannerInfo2 = new OA_Dll_BannerInfo();
        $oBannerInfo2->campaignId = $oCampaignInfo->campaignId;
        $oBannerInfo2->storageType = 'web';
        $oBannerInfo2->aImage = array(
            'filename' => '1x1.gif',
            'content'  => $this->binaryGif
        );

        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo2),
                          $dllBannerPartialMock->getLastError());

        $doBanners = OA_Dal::staticGetDO('banners', $oBannerInfo2->bannerId);
        $this->assertEqual($doBanners->width, 1);
        $this->assertEqual($doBanners->height, 1);
        $this->assertEqual($doBanners->contenttype, 'gif');

        $img = $GLOBALS['_MAX']['CONF']['store']['webDir'].'/'.$doBanners->filename;
        $this->assertEqual(file_get_contents($img), $this->binaryGif);

        $this->assertTrue(unlink($img));

        // Modify to different gif
        $oBannerInfo2 = new OA_Dll_BannerInfo();
        $oBannerInfo2->bannerId = (int)$doBanners->bannerid;
        $oBannerInfo2->aImage = array(
            'filename' => 'foo.gif',
            'content'  => $this->binaryGif
        );

        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo2),
                          $dllBannerPartialMock->getLastError());

        $doBanners = OA_Dal::staticGetDO('banners', $oBannerInfo2->bannerId);
        $this->assertEqual($doBanners->width, 1);
        $this->assertEqual($doBanners->height, 1);
        $this->assertEqual($doBanners->contenttype, 'gif');

        $img = $GLOBALS['_MAX']['CONF']['store']['webDir'].'/'.$doBanners->filename;
        $this->assertEqual(file_get_contents($img), $this->binaryGif);
        $this->assertTrue(unlink($img));

        // Add mangled banner
        $oBannerInfo2 = new OA_Dll_BannerInfo();
        $oBannerInfo2->campaignId = $oCampaignInfo->campaignId;
        $oBannerInfo2->storageType = 'sql';
        $oBannerInfo2->aImage = array(
            'filename' => 'test.gif',
            'content'  => 'foobar'
        );

        $this->assertTrue((!$dllBannerPartialMock->modify($oBannerInfo2) &&
                          $dllBannerPartialMock->getLastError() == $this->unknownFormatError),
            $this->_getMethodShouldReturnError($this->unknownFormatError));

        // Modify not existing id
        $this->assertTrue((!$dllBannerPartialMock->modify($oBannerInfo) &&
                          $dllBannerPartialMock->getLastError() == $this->unknownIdError),
            $this->_getMethodShouldReturnError($this->unknownIdError));

        // Delete not existing id
        $this->assertTrue((!$dllBannerPartialMock->delete($oBannerInfo->bannerId) &&
                           $dllBannerPartialMock->getLastError() == $this->unknownIdError),
            $this->_getMethodShouldReturnError($this->unknownIdError));

        $dllBannerPartialMock->tally();
    }

    /**
     * A method to test get and getList method.
     */
    function testGetAndGetList()
    {
        $dllAdvertiserPartialMock = new PartialMockOA_Dll_Advertiser_BannerTest($this);
        $dllCampaignPartialMock   = new PartialMockOA_Dll_Campaign_BannerTest($this);
        $dllBannerPartialMock     = new PartialMockOA_Dll_Banner($this);

        $dllAdvertiserPartialMock->setReturnValue('getDefaultAgencyId', $this->agencyId);
        $dllAdvertiserPartialMock->setReturnValue('checkPermissions', true);
        $dllAdvertiserPartialMock->expectCallCount('checkPermissions', 2);

        $dllCampaignPartialMock->setReturnValue('checkPermissions', true);
        $dllCampaignPartialMock->expectCallCount('checkPermissions', 1);

        $dllBannerPartialMock->setReturnValue('checkPermissions', true);
        $dllBannerPartialMock->expectCallCount('checkPermissions', 6);

        $oAdvertiserInfo = new OA_Dll_AdvertiserInfo();
        $oAdvertiserInfo->advertiserName = 'test Advertiser name';
        $oAdvertiserInfo->agencyId       = $this->agencyId;

        $this->assertTrue($dllAdvertiserPartialMock->modify($oAdvertiserInfo),
                          $dllAdvertiserPartialMock->getLastError());

        $oCampaignInfo = new OA_Dll_CampaignInfo();

        $oCampaignInfo->advertiserId = $oAdvertiserInfo->advertiserId;

        $this->assertTrue($dllCampaignPartialMock->modify($oCampaignInfo),
                          $dllCampaignPartialMock->getLastError());


        // Add
        $oBannerInfo1               = new OA_Dll_BannerInfo();
        $oBannerInfo1->bannerName   = 'test name 1';
        $oBannerInfo1->storageType  = 'url';
        $oBannerInfo1->imageURL     = 'image url';
        $oBannerInfo1->htmlTemplate = 'html Template';
        $oBannerInfo1->width        = 2;
        $oBannerInfo1->height       = 3;
        $oBannerInfo1->url          = 'url';
        $oBannerInfo1->campaignId   = $oCampaignInfo->campaignId;

        $oBannerInfo2               = new OA_Dll_BannerInfo();
        $oBannerInfo2->bannerName = 'test name 2';
        $oBannerInfo2->campaignId   = $oCampaignInfo->campaignId;
        // Add
        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo1),
                          $dllBannerPartialMock->getLastError());

        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo2),
                          $dllBannerPartialMock->getLastError());

        $oBannerInfo1Get = null;
        $oBannerInfo2Get = null;
        // Get
        $this->assertTrue($dllBannerPartialMock->getBanner($oBannerInfo1->bannerId,
                                                                   $oBannerInfo1Get),
                          $dllBannerPartialMock->getLastError());
        $this->assertTrue($dllBannerPartialMock->getBanner($oBannerInfo2->bannerId,
                                                                   $oBannerInfo2Get),
                          $dllBannerPartialMock->getLastError());

        // Check field value
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'bannerName');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'storageType');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'imageURL');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'htmlTemplate');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'width');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'height');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'url');
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'campaignId');
        $this->assertFieldEqual($oBannerInfo2, $oBannerInfo2Get, 'bannerName');

        // Get List
        $aBannerList = array();
        $this->assertTrue($dllBannerPartialMock->getBannerListByCampaignId($oCampaignInfo->campaignId,
                                                                           $aBannerList),
                          $dllBannerPartialMock->getLastError());
        $this->assertEqual(count($aBannerList) == 2,
                           '2 records should be returned');
        $oBannerInfo1Get = $aBannerList[0];
        $oBannerInfo2Get = $aBannerList[1];
        if ($oBannerInfo1->bannerId == $oBannerInfo2Get->bannerId) {
            $oBannerInfo1Get = $aBannerList[1];
            $oBannerInfo2Get = $aBannerList[0];
        }
        // Check field value from list
        $this->assertFieldEqual($oBannerInfo1, $oBannerInfo1Get, 'bannerName');
        $this->assertFieldEqual($oBannerInfo2, $oBannerInfo2Get, 'bannerName');


        // Delete
        $this->assertTrue($dllBannerPartialMock->delete($oBannerInfo1->bannerId),
            $dllBannerPartialMock->getLastError());

        // Get not existing id
        $this->assertTrue((!$dllBannerPartialMock->getBanner($oBannerInfo1->bannerId,
                                                                     $oBannerInfo1Get) &&
                          $dllBannerPartialMock->getLastError() == $this->unknownIdError),
            $this->_getMethodShouldReturnError($this->unknownIdError));

        $dllBannerPartialMock->tally();
    }

    /**
     * Method to run all tests for banner statistics
     *
     * @access private
     *
     * @param string $methodName  Method name in Dll
     */
    function _testStatistics($methodName)
    {
        $dllAdvertiserPartialMock = new PartialMockOA_Dll_Advertiser_BannerTest($this);
        $dllCampaignPartialMock = new PartialMockOA_Dll_Campaign_BannerTest($this);
        $dllBannerPartialMock = new PartialMockOA_Dll_Banner($this);

        $dllAdvertiserPartialMock->setReturnValue('getDefaultAgencyId', $this->agencyId);
        $dllAdvertiserPartialMock->setReturnValue('checkPermissions', true);
        $dllAdvertiserPartialMock->expectCallCount('checkPermissions', 2);

        $dllCampaignPartialMock->setReturnValue('checkPermissions', true);
        $dllCampaignPartialMock->expectCallCount('checkPermissions', 1);

        $dllBannerPartialMock->setReturnValue('checkPermissions', true);
        $dllBannerPartialMock->expectCallCount('checkPermissions', 5);

        $oAdvertiserInfo = new OA_Dll_AdvertiserInfo();
        $oAdvertiserInfo->advertiserName = 'test Advertiser name';
        $oAdvertiserInfo->agencyId       = $this->agencyId;

        $this->assertTrue($dllAdvertiserPartialMock->modify($oAdvertiserInfo),
                          $dllAdvertiserPartialMock->getLastError());

        $oCampaignInfo = new OA_Dll_CampaignInfo();

        $oCampaignInfo->advertiserId = $oAdvertiserInfo->advertiserId;

        // Add
        $this->assertTrue($dllCampaignPartialMock->modify($oCampaignInfo),
                          $dllCampaignPartialMock->getLastError());

        $oBannerInfo = new OA_Dll_BannerInfo();
        $oBannerInfo->campaignId = $oCampaignInfo->campaignId;

        $this->assertTrue($dllBannerPartialMock->modify($oBannerInfo),
                          $dllBannerPartialMock->getLastError());

        // Get no data
        $rsBannerStatistics = null;
        $this->assertTrue($dllBannerPartialMock->$methodName(
            $oBannerInfo->bannerId, new Date('2001-12-01'), new Date('2007-09-19'), false,
            $rsBannerStatistics), $dllBannerPartialMock->getLastError());

        $this->assertTrue(isset($rsBannerStatistics));
        if (is_array($rsBannerStatistics)) {
            $this->assertEqual(count($rsBannerStatistics), 0, 'No records should be returned');
        } else {
            $this->assertEqual($rsBannerStatistics->getRowCount(), 0, 'No records should be returned');
        }

        // Test for wrong date order
        $rsBannerStatistics = null;
        $this->assertTrue((!$dllBannerPartialMock->$methodName(
                $oBannerInfo->bannerId, new Date('2007-09-19'),  new Date('2001-12-01'), false,
                $rsBannerStatistics) &&
            $dllBannerPartialMock->getLastError() == $this->wrongDateError),
            $this->_getMethodShouldReturnError($this->wrongDateError));

        // Delete
        $this->assertTrue($dllBannerPartialMock->delete($oBannerInfo->bannerId),
            $dllBannerPartialMock->getLastError());

        // Test statistics for not existing id
        $rsBannerStatistics = null;
        $this->assertTrue((!$dllBannerPartialMock->$methodName(
                $oBannerInfo->bannerId, new Date('2001-12-01'),  new Date('2007-09-19'), false,
                $rsBannerStatistics) &&
            $dllBannerPartialMock->getLastError() == $this->unknownIdError),
            $this->_getMethodShouldReturnError($this->unknownIdError));

        $dllBannerPartialMock->tally();
    }

    /**
     * A method to test getBannerDailyStatistics.
     */
    function testDailyStatistics()
    {
        $this->_testStatistics('getBannerDailyStatistics');
    }

    /**
     * A method to test getBannerHourlyStatistics.
     */
    function testHourlyStatistics()
    {
        $this->_testStatistics('getBannerHourlyStatistics');
    }

    /**
     * A method to test getBannerPublisherStatistics.
     */
    function testPublisherStatistics()
    {
        $this->_testStatistics('getBannerPublisherStatistics');
    }

    /**
     * A method to test getBannerZoneStatistics.
     */
    function testZoneStatistics()
    {
        $this->_testStatistics('getBannerZoneStatistics');
    }

}

?>
