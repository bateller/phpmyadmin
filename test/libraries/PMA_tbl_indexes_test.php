<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Tests for libraries/tbl_indexes.lib.php
 *
 * @package PhpMyAdmin-test
 */

/*
 * Include to test.
 */
require_once 'libraries/Template.class.php';
require_once 'libraries/Util.class.php';
require_once 'libraries/Table.class.php';
require_once 'libraries/Index.class.php';
require_once 'libraries/Message.class.php';
require_once 'libraries/database_interface.inc.php';
require_once 'libraries/php-gettext/gettext.inc';
require_once 'libraries/relation.lib.php';
require_once 'libraries/url_generating.lib.php';
require_once 'libraries/Theme.class.php';
require_once 'libraries/sanitizing.lib.php';

/**
 * Tests for libraries/tbl_indexes.lib.php
 *
 * @package PhpMyAdmin-test
 */
class PMA_TblIndexTest extends PHPUnit_Framework_TestCase
{

    /**
     * Setup function for test cases
     *
     * @access protected
     * @return void
     */
    protected function setUp()
    {
        /**
         * SET these to avoid undefined index error
         */
        $GLOBALS['server'] = 1;
        $GLOBALS['cfg']['Server']['pmadb'] = '';
        $GLOBALS['pmaThemeImage'] = 'theme/';
        $GLOBALS['cfg']['ServerDefault'] = "server";
        $GLOBALS['cfg']['ShowHint'] = true;

        $dbi = $this->getMockBuilder('PMA_DatabaseInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $indexs = array(
            array(
                "Schema" => "Schema1",
                "Key_name"=>"Key_name1",
                "Column_name"=>"Column_name1"
            ),
            array(
                "Schema" => "Schema2",
                "Key_name"=>"Key_name2",
                "Column_name"=>"Column_name2"
            ),
            array(
                "Schema" => "Schema3",
                "Key_name"=>"Key_name3",
                "Column_name"=>"Column_name3"
            ),
        );

        $dbi->expects($this->any())->method('getTableIndexes')
            ->will($this->returnValue($indexs));

        $GLOBALS['dbi'] = $dbi;

        //$_SESSION
        $_SESSION['PMA_Theme'] = PMA_Theme::load('./themes/pmahomme');
        $_SESSION['PMA_Theme'] = new PMA_Theme();
    }

    /**
     * Tests for PMA_getSqlQueryForIndexCreateOrEdit() method.
     *
     * @return void
     * @test
     */
    public function testPMAGetSqlQueryForIndexCreateOrEdit()
    {
        $db = "pma_db";
        $table = "pma_table";
        $index = new PMA_Index();
        $error = false;

        $_REQUEST['old_index'] = "PRIMARY";

        $table = new PMA_Table($table, $db);
        $sql = $table->getSqlQueryForIndexCreateOrEdit($index, $error);

        $this->assertEquals(
            "ALTER TABLE `pma_db`.`pma_table` DROP PRIMARY KEY, ADD UNIQUE ;",
            $sql
        );
    }

    /**
     * Tests for PMA_getHtmlForIndexForm() method.
     *
     * @return void
     * @test
     */
    public function testPMAGetHtmlForIndexForm()
    {
        /**
         * @todo Find out a better method to test for HTML
         *
         * $fields = array("field_name" => "field_type");
         * $index = new PMA_Index();
         * $form_params = array(
         *     'db' => 'db',
         *     'table' => 'table',
         *     'create_index' => 1,
         * );
         * $add_fields = 3;
         *
         * $html = PMA_getHtmlForIndexForm(
         *     $fields, $index, $form_params, $add_fields
         * );
         *
         * //PMA_URL_getHiddenInputs
         * $this->assertContains(
         *     PMA_URL_getHiddenInputs($form_params),
         *     $html
         * );
         *
         * //Index name
         * $this->assertContains(
         *     __('Index name:'),
         *     $html
         * );
         * $doc_html = PMA_Util::showHint(
         *     PMA_Message::notice(
         *         __(
         *             '"PRIMARY" <b>must</b> be the name of'
         *             . ' and <b>only of</b> a primary key!'
         *         )
         *     )
         * );
         * $this->assertContains(
         *     $doc_html,
         *     $html
         * );
         *
         * //Index name
         * $this->assertContains(
         *     __('Index name:'),
         *     $html
         * );
         * $this->assertContains(
         *     PMA_Util::showMySQLDocu('ALTER_TABLE'),
         *     $html
         * );
         *
         * //generateIndexSelector
         * $this->assertContains(
         *     PMA\Template::trim($index->generateIndexChoiceSelector(false)),
         *     $html
         * );
         *
         * //items
         * $this->assertContains(
         *     __('Column'),
         *     $html
         * );
         * $this->assertContains(
         *     __('Size'),
         *     $html
         * );
         * $this->assertContains(
         *     sprintf(__('Add %s column(s) to index'), 1),
         *     $html
         * );
         *
         * //$field_name & $field_type
         * $this->assertContains(
         *     "field_name",
         *     $html
         * );
         * $this->assertContains(
         *     "field_type",
         *     $html
         * );
         */
        $this->markTestIncomplete('Not yet implemented!');
    }
}
