<?php
declare(strict_types=1);

namespace App;

require __DIR__ . '/vendor/autoload.php';

use App\cli\AppCLI;

class AppConfig{
    public static string $path = __DIR__;
}

if (php_sapi_name() != 'cli') {
    die("This script must be run from the command line.\n");
}

error_reporting(E_ERROR | E_PARSE);

$cli = new AppCLI();

if ($argc < 2) {
    echo "No command provided.\n";
    $cli->showHelp();
    exit(1);
}

$command = $argv[1];

switch ($command) {
    case 'add-item':
        if ($argc < 3) {
            echo "Usage: add-item [item-id]\n";
            exit(1);
        }
        $item = $argv[2];
        $cli->add($item);
        break;
    case 'remove-item':
        if ($argc < 3) {
            echo "Usage: remove-item [item-id]\n";
            exit(1);
        }
        $item = $argv[2];
        $cli->remove($item);
        break;
    case 'get-items':
        if ($argc < 2) {
            echo "Usage: get-items\n";
            exit(1);
        }
        $cli->getItems();
        break;
    case 'get-checkout-summary':
        if ($argc < 2) {
            echo "Usage: get-checkout-summary\n";
            exit(1);
        }
        $cli->getCheckoutSummary();
        break;
    case 'help':
        $cli->showHelp();
        break;
    default:
        echo "Unknown command: $command\n";
        $cli->showHelp();
        exit(1);
}