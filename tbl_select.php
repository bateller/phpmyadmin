<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Handles table search tab
 *
 * display table search form, create SQL query from form data
 * and call PMA_executeQueryAndSendQueryResponse() to execute it
 *
 * @package PhpMyAdmin
 */

/**
 * Gets some core libraries
 */
require_once 'libraries/common.inc.php';
require_once 'libraries/mysql_charsets.inc.php';
require_once 'libraries/TableSearch.class.php';
require_once 'libraries/sql.lib.php';
require_once 'libraries/bookmark.lib.php';

$response = PMA_Response::getInstance();
$header   = $response->getHeader();
$scripts  = $header->getScripts();
$scripts->addFile('makegrid.js');
$scripts->addFile('sql.js');
$scripts->addFile('tbl_select.js');
$scripts->addFile('tbl_change.js');
$scripts->addFile('jquery/jquery-ui-timepicker-addon.js');
$scripts->addFile('jquery/jquery.uitablefilter.js');
$scripts->addFile('gis_data_editor.js');

$table_search = new PMA_TableSearch($db, $table, "normal");

// Request to column min-max value.
if (isset($_REQUEST['range_search'])) {
    $response = PMA_Response::getInstance();
    $min_max = $table_search->getColumnMinMax($_REQUEST['column']);
    $response->addJSON('column_data', $min_max);
    exit;
}

/**
 * No selection criteria received -> display the selection form
 */
if (! isset($_POST['columnsToDisplay']) && ! isset($_POST['displayAllColumns'])) {
    // Gets some core libraries
    include_once 'libraries/tbl_common.inc.php';
    //$err_url   = 'tbl_select.php' . $err_url;
    $url_query .= '&amp;goto=tbl_select.php&amp;back=tbl_select.php';
    /**
     * Gets table's information
     */
    include_once 'libraries/tbl_info.inc.php';

    if (! isset($goto)) {
        $goto = PMA_Util::getScriptNameForOption(
            $GLOBALS['cfg']['DefaultTabTable'], 'table'
        );
    }
    // Defines the url to return to in case of error in the next sql statement
    $err_url   = $goto . PMA_URL_getCommon(array('db' => $db, 'table' => $table));
    // Displays the table search form
    $response->addHTML($table_search->getSecondaryTabs());
    $response->addHTML($table_search->getSelectionForm($goto));

} else {

    /**
     * Selection criteria have been submitted -> do the work
     */
    $sql_query = $table_search->buildSqlQuery();

    /**
     * Parse and analyze the query
     */
    include_once 'libraries/parse_analyze.inc.php';

    PMA_executeQueryAndSendQueryResponse(
        $analyzed_sql_results, // analyzed_sql_results
        false, // is_gotofile
        $db, // db
        $table, // table
        null, // find_real_end
        null, // sql_query_for_bookmark
        null, // extra_data
        null, // message_to_show
        null, // message
        null, // sql_data
        $GLOBALS['goto'], // goto
        $pmaThemeImage, // pmaThemeImage
        null, // disp_query
        null, // disp_message
        null, // query_type
        $sql_query, // sql_query
        null, // selectedTables
        null // complete_query
    );
}
