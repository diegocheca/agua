<?php
if(isset($_SERVER["REMOTE_ADDR"]))
{
	header("Location: http://tuweb.com");
}

require dirname(__FILE__) . "/index.php";