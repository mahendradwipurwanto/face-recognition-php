<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['scan'] = 'home/scan';

$route['default_controller'] = 'home';
$route['404_override'] = 'utility/not_found';
$route['translate_uri_dashes'] = false;
