<?php

require_once(__DIR__ . '/../../bootstrap.php');

$configManager = \WWII\Config\ConfigManager::getInstance();
$serviceManager = new \WWII\Service\ServiceManager();
$serviceManager->setConfigManager($configManager);
