<?php
include_once("connect_db.php");
$data = array();
$index = array();
$res = "";
$query = mysqli_query($conn,"SELECT column_id, column_parent, column_name FROM report_columns ORDER BY column_name");
while ($row = mysqli_fetch_assoc($query)) {
    $id = $row["column_id"];
    $parent_id = $row["column_parent"] === NULL ? "NULL" : $row["column_parent"];
    $data[$id] = $row;
    $index[$parent_id][] = $id;
}
function display_child_nodes($parent_id, $level)
{
    global $data, $index, $res;
    $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
        foreach ($index[$parent_id] as $id) {
            $res .= str_repeat("→", $level) . $data[$id]["column_name"] . " [ ".$data[$id]["column_id"]." ],";
            display_child_nodes($id, $level + 1);
        }
    }
}
display_child_nodes(NULL, 0);

echo $res;
?>