<?php

$semana = date('w');

echo SUBSTRING("0100000", $semana-1, 1);
?>