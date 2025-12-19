<?php
/* phpMyAdmin configuration */

$cfg['blowfish_secret'] = 'your_random_secret_key_123'; /* YOU MUST FILL IN THIS FOR COOKIE AUTH! */

$i = 0;
$i++;

/* Server: AWS RDS */
$cfg['Servers'][$i]['host'] = 'sitesync-db.ctqiowcw4yhd.ap-south-1.rds.amazonaws.com';
$cfg['Servers'][$i]['port'] = '3306';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = false;

/* Directories */
$cfg['TempDir'] = '/tmp';
