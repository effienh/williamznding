<?php
include __DIR__ . "/../includes/db.php";
include __DIR__."/../includes/sql.php";
echo sqlselect("SELECT Temperature FROM ColdRoomTemperatures WHERE ColdRoomSensorNumber = 1", array())[0]['Temperature'];
?>
