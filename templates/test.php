<?php
$handle = popen("./testscript.py", "r");
$read = fread($handle, 2096);
echo $read;
pclose($handle);

?>
