<?php

$token = "some-sweet-token-dvjn5jns^k6se44ffm^xd5nvx5m=a3";
$adminToken = "some-sweet-admin-token-4k6j+n%fm-346j+n6gk%fm-kfmfma33kvt";
$salt = 'your-salt-dflkgjjxfbfvklndrv';

$tokenHASH = password_hash($token, PASSWORD_DEFAULT);
$adminTokenHASH = password_hash($adminToken, PASSWORD_DEFAULT);


$users = array(
  'resident' => password_hash('residentpass', PASSWORD_DEFAULT),
  'admin' => password_hash('residentpass', PASSWORD_DEFAULT)
);



