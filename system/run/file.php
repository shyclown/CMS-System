<?php
$root = $_SERVER["DOCUMENT_ROOT"];
if (!file_exists($root.'/files')) {
    mkdir($root.'/files', 0777, true);
    echo 'created file';
}
?>
