<?php

const NODE_MODULES_FOLDER_NAME = 'node_modules';
const PATH_TO_NODE_MODULES = 'tests' . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'node_modules';
    
if (@lstat(NODE_MODULES_FOLDER_NAME))
{
    if (is_link(NODE_MODULES_FOLDER_NAME) || is_dir(NODE_MODULES_FOLDER_NAME)) {
        echo '> `' . NODE_MODULES_FOLDER_NAME . '` already exists as a link or folder, keeping existing as may be intentional.' . PHP_EOL;
        exit(0);
    } else {
        echo '> Invalid symlink `' . NODE_MODULES_FOLDER_NAME . '` detected, recreating...' . PHP_EOL;
        if (!@unlink(NODE_MODULES_FOLDER_NAME)) {
            echo '> Could not delete file `' . NODE_MODULES_FOLDER_NAME . '`.' . PHP_EOL;
            exit(1);
        }
    }
}

$success = @symlink(PATH_TO_NODE_MODULES, NODE_MODULES_FOLDER_NAME);

if (!$success && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    echo '> This system is running Windows, creation of links requires elevated privileges,' . PHP_EOL;
    echo '> and target path to exist. Fallback to NTFS Junction:' . PHP_EOL;
    exec(sprintf('mklink /J %s %s 2> NUL', NODE_MODULES_FOLDER_NAME, PATH_TO_NODE_MODULES), $output, $returnCode);
    $success = $returnCode === 0;
    if (!$success) {
	echo '> Failed o create the required symlink' . PHP_EOL;
        exit(2);
    }
}

$path = @readlink(NODE_MODULES_FOLDER_NAME);
/* check if link points to the intended directory */
if ($path && realpath($path) === realpath(PATH_TO_NODE_MODULES)) {
    echo '> Successfully created the symlink.' . PHP_EOL;
    exit(0);
}

echo '> Failed to create the symlink to `' . NODE_MODULES_FOLDER_NAME . '`.' . PHP_EOL;
exit(3);
