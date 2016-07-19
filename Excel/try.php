<?php
include_once("ExcelWriter.php");

$title = "Sheet1";
$colors = array("red", "blue", "green", "yellow", "orange", "purple");

ob_start();
$xls = new Excel($title);
$xls->top();
$xls->home();
foreach ($colors as $color)
{
$xls->label($color);
$xls->right();
#$xls->down();
};
$data = ob_get_clean();
$xls->send();
file_put_contents('report.xls', $data);

?>