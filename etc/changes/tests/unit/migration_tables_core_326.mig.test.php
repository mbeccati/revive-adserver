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

require_once MAX_PATH . '/etc/changes/migration_tables_core_326.php';
require_once MAX_PATH . '/lib/OA/DB/Sql.php';
require_once MAX_PATH . '/etc/changes/tests/unit/MigrationTest.php';
require_once MAX_PATH . '/lib/OA/Dal/DataGenerator.php';

/**
 * Test for migration class #326.
 *
 * @package    changes
 * @subpackage TestSuite
 */
class Migration_tables_core_326Test extends MigrationTest
{
    var $path;
    var $prefix;

    /**
     * The constructor method.
     */
    function Test_DB_Upgrade()
    {
        parent::__construct();

        $this->path   = MAX_PATH.'/etc/changes/';
        $this->prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];
    }
    function test_executeTasksTablesAlter()
    {
        $this->assertTrue($this->initDatabase(325, array('campaigns')).'failed to created version 325 of campaigns table');


        $tblCampaigns = $this->oDbh->quoteIdentifier($this->prefix.'campaigns',true);
        // Insert some data to test the upgrade from... we know the schema being used so we can directly insert
        $this->oDbh->exec("INSERT INTO {$tblCampaigns} VALUES (1,'campaign one',   1, 100, 10, 1, '2000-01-01', '2000-01-01', 't', 'h', 1, 1, 'f', 'f')");
        $this->oDbh->exec("INSERT INTO {$tblCampaigns} VALUES (2,'campaign two',   1, 100, 10, 1, '2000-01-01', '2000-01-01', 't', 'm', 1, 1, 'f', 'f')");
        $this->oDbh->exec("INSERT INTO {$tblCampaigns} VALUES (3,'campaign three', 1, -1, -1, -1, '2000-01-01', '2000-01-01', 't', 'l', 1, 1, 'f', 'f')");
        $this->oDbh->exec("INSERT INTO {$tblCampaigns} VALUES (4,'campaign four',   1, 100, 10, 1, '2000-01-01', '2000-01-01', 't', 'h', 1, 1, 'f', 'f')");
        $this->oDbh->exec("INSERT INTO {$tblCampaigns} VALUES (5,'campaign five',   1, 100, 10, 1, '2000-01-01', '2000-01-01', 't', 'm', 1, 1, 'f', 'f')");
        $this->oDbh->exec("INSERT INTO {$tblCampaigns} VALUES (6,'campaign six',   1, -1, -1, -1, '2000-01-01', '2000-01-01', 't', 'l', 1, 1, 'f', 'f')");

        Mock::generatePartial(
            'OA_DB_UpgradeAuditor',
            $mockAuditor = 'OA_DB_UpgradeAuditor'.rand(),
            array('logAuditAction', 'setKeyParams')
        );

        $oLogger = new OA_UpgradeLogger();
        $oLogger->setLogFile('DB_Upgrade.test.log');

        $oDB_Upgrade = new OA_DB_Upgrade($oLogger);

        $oDB_Upgrade->oAuditor = new $mockAuditor($this);
        $oDB_Upgrade->oAuditor->setReturnValue('logAuditAction', true);
        $oDB_Upgrade->oAuditor->setReturnValue('setKeyParams', true);

        $oDB_Upgrade->init('constructive', 'tables_core', 326);

        $aDef325 = $this->oaTable->aDefinition;

        $oDB_Upgrade->aDBTables         = $oDB_Upgrade->_listTables();
        $this->assertTrue($oDB_Upgrade->_verifyTasksTablesAlter(),'failed _verifyTasksTablesAlter: change field');
        $this->assertTrue($oDB_Upgrade->_executeTasksTablesAlter(),'failed _executeTasksTablesAlter: change field');

        $aDefDB = $oDB_Upgrade->oSchema->getDefinitionFromDatabase(array($this->prefix.'campaigns'));
        $aDiff = $oDB_Upgrade->oSchema->compareDefinitions($oDB_Upgrade->aDefinitionNew['tables']['campaigns'], $aDefDB['tables'][$this->prefix.'campaigns']);

        $this->assertEqual(count($aDiff),0,'comparison failed');

        $aResults = $this->oDbh->queryAll("SELECT * FROM ".$tblCampaigns);

        $this->assertIsa($aResults, 'array');
        $expected = array(1 => '5', 2 => '3', 3 => '0', 4 => '5', 5 => '3', 6 => '0');
        foreach ($aResults as $idx => $aRow) {
            $this->assertEqual($aRow['priority'], $expected[$aRow['campaignid']], ' unexpected campaign priority value detected after upgrade');
        }
    }

    /**
     * internal function to return an initialised db_upgrade object for testing
     *
     * @param string $timing
     * @return object
     */
    function _newDBUpgradeObject($timing='constructive')
    {
        $oDB_Upgrade->initMDB2Schema();
        $oDB_Upgrade->timingStr = $timing;
        $oDB_Upgrade->timingInt = ($timing ? 0 : 1);
        $oDB_Upgrade->prefix = $this->prefix;
        $oDB_Upgrade->schema = 'tables_core_326';
        $oDB_Upgrade->versionFrom = 1;
        $oDB_Upgrade->versionTo = 2;
        $oDB_Upgrade->logFile = MAX_PATH . "/var/test.log";
        $oDBAuditor   = new OA_DB_UpgradeAuditor();
        $this->assertTrue($oDBAuditor->init($oDB_Upgrade->oSchema->db), 'error initialising upgrade auditor, probable error creating database action table');
        $oDBAuditor->setKeyParams(array('schema_name'=>$oDB_Upgrade->schema,
                                        'version'=>$oDB_Upgrade->versionTo,
                                        'timing'=>$oDB_Upgrade->timingInt
                                        ));
        $oDB_Upgrade->oAuditor = &$oDBAuditor;
        return $oDB_Upgrade;
    }

}
?>
