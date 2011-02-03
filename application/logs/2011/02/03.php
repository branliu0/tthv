<?php defined('SYSPATH') or die('No direct script access.'); ?>

2011-02-03 00:36:25 --- ERROR: ReflectionException [ -1 ]: Class controller_cases does not exist ~ SYSPATH\classes\kohana\request.php [ 1178 ]
2011-02-03 00:50:24 --- ERROR: ReflectionException [ -1 ]: Class controller_cases does not exist ~ SYSPATH\classes\kohana\request.php [ 1178 ]
2011-02-03 00:50:26 --- ERROR: ReflectionException [ -1 ]: Class controller_cases does not exist ~ SYSPATH\classes\kohana\request.php [ 1178 ]
2011-02-03 00:50:32 --- ERROR: Database_Exception [ 1054 ]: Unknown column 'phc' in 'field list' [ SELECT COUNT(*) as total, phc FROM cases GROUP BY phc_name ] ~ MODPATH\database\classes\kohana\database\mysql.php [ 179 ]
2011-02-03 00:50:58 --- ERROR: Database_Exception [ 1054 ]: Unknown column 'phc' in 'field list' [ SELECT COUNT(*) as total, phc FROM cases GROUP BY phc_name ] ~ MODPATH\database\classes\kohana\database\mysql.php [ 179 ]
2011-02-03 00:51:02 --- ERROR: Database_Exception [ 1054 ]: Unknown column 'phc' in 'field list' [ SELECT COUNT(*) as total, phc FROM cases GROUP BY phc_name ] ~ MODPATH\database\classes\kohana\database\mysql.php [ 179 ]
2011-02-03 00:56:35 --- ERROR: ErrorException [ 1 ]: Call to undefined method Model_Case::select_by_phc_name() ~ APPPATH\classes\controller\case.php [ 14 ]
2011-02-03 01:18:20 --- ERROR: ReflectionException [ 0 ]: Function alphanumeric() does not exist ~ SYSPATH\classes\kohana\validate.php [ 923 ]
2011-02-03 01:27:10 --- ERROR: Kohana_Request_Exception [ 0 ]: Unable to find a route to match the URI: case/phc/index.php ~ SYSPATH\classes\kohana\request.php [ 676 ]
2011-02-03 01:29:35 --- ERROR: ErrorException [ 4096 ]: Argument 3 passed to Kohana_Validate::rule() must be an array, string given, called in C:\xampp\htdocs\TTHV\application\classes\controller\case.php on line 39 and defined ~ SYSPATH\classes\kohana\validate.php [ 660 ]
2011-02-03 01:29:54 --- ERROR: ErrorException [ 2 ]: preg_match() [function.preg-match]: Unknown modifier '+' ~ SYSPATH\classes\kohana\validate.php [ 50 ]