<?php
/**
 * Command line script for executing PHPMD during a Travis build.
 *
 * This CLI is used instead normal travis.yml execution to avoid error in travis build when
 * PHPMD exits with 2.
 *
 * @copyright  Copyright (C) 2008 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @example: php .travis/phpmd.php component/ libraries/
 */

// Only run on the CLI SAPI
(php_sapi_name() == 'cli' ?: die('CLI only'));

// Script defines
define('REPO_BASE', dirname(__DIR__));

// Welcome message
fwrite(STDOUT, "\033[32;1mInitializing PHPMD checks.\033[0m\n");

// Check for arguments
if (2 > count($argv))
{
    fwrite(STDOUT, "\033[32;1mPlease add path to check with PHP Mess Detector.\033[0m\n");
    exit(1);
}

for($i=1;$i < count($argv);$i++)
{
    $folderToCheck = REPO_BASE . '/' .$argv[$i];

    if (!file_exists($folderToCheck))
    {
        fwrite(STDOUT, "\033[31;1mFolder: " . $argv[$i] . " does not exist\033[0m\n");
        continue;
    }

    fwrite(STDOUT, "\033[32;1m- Checking errors at: " . $argv[$i] . "\033[0m\n");
    $messDetectorWarnings = shell_exec(REPO_BASE . '/vendor/bin/phpmd ' . $folderToCheck . ' text codesize,unusedcode,controversial');


    if ($messDetectorWarnings)
    {
        fwrite(STDOUT, $messDetectorWarnings);
    }
}

exit(0);