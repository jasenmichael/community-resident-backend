<?php

$token = "some-sweet-token-dvjn5jns^k6se44ffm^xd5nvx5m=a3";
$adminToken = "some-sweet-admin-token-4k6j+n%fm-346j+n6gk%fm-kfmfma33kvt";

$users = array(
  'resident' => password_hash('residentpass', PASSWORD_DEFAULT),
  'admin' => password_hash('adminpass', PASSWORD_DEFAULT)
);



