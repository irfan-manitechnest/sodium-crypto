<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
if (!extension_loaded('sodium')) {
    fwrite(STDERR, "ERROR: The ext-sodium extension is not loaded.\n");
    exit(1);
}
