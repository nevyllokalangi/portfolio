<?php
// User roles
define('ROLE_BASIC', 'basic');
define('ROLE_EDITOR', 'editor');
define('ROLE_MAINTENANCE', 'maintenance');
define('ROLE_ADMIN', 'admin');

$role_hierarchy = [
  ROLE_BASIC => 1,
  ROLE_EDITOR => 2,
  ROLE_MAINTENANCE => 3,
  ROLE_ADMIN => 4
];