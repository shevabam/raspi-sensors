<?php
require 'autoload.php';

session_start();

$Cache = new Cache();

$Config = new Config();
$Config->checkInstall();


$Param = new Param($Config->get('db'));
$Sensor = new Sensor($Config->get('db'));
$SensorGroup = new SensorGroup($Config->get('db'));
$Temperature = new Temperature($Config->get('db'));
