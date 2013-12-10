<?php

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('reports', new admin_externalpage('logreports', 'Log Reports', "$CFG->wwwroot/$CFG->admin/report/logreports/index.php",'report/courseoverview:view'));//get_string('pluginname', 'logreports')
// $ADMIN->add('root', new admin_externalpage('TWO', 'Log Reports', "$CFG->wwwroot/$CFG->admin/report/logreports/test.php",'report/courseoverview:view'));//get_string('pluginname', 'logreports')
// $ADMIN->add('root', new admin_category('newreports', 'Log Reportsssss'));

