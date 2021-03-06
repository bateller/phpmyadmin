<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * handles creation of the chart
 *
 * @package PhpMyAdmin
 */

namespace PMA;

require_once 'libraries/di/Container.class.php';
require_once 'libraries/controllers/TableChartController.class.php';

$container = DI\Container::getDefaultContainer();
$container->factory('PMA\Controllers\Table\TableChartController');
$container->alias(
    'TableChartController', 'PMA\Controllers\Table\TableChartController'
);

/* Define dependencies for the concerned controller */
$dependency_definitions = array(
    "sql_query" => &$GLOBALS['sql_query'],
    "url_query" => &$GLOBALS['url_query'],
    "cfg" => &$GLOBALS['cfg']
);

/** @var Controllers\Table\TableChartController $controller */
$controller = $container->get('TableChartController', $dependency_definitions);
$controller->indexAction();
