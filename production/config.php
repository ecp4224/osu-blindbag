<?php

if ( ! defined('EXT')){
exit('Invalid file request');
}

//MySQL config
$conf['mysql_ip'] = "";
$conf['mysql_db'] = "";
$conf['mysql_user'] = "";
$conf['mysql_pass'] = "";

//The amount of days to wait for each user
$conf['blindbag_days'] = 6;
$conf['blindbag_cache-songs'] = false;

?>