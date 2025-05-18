<?php
use LocalClasses\IblockSectionsController,
    Bitrix\Main\Config\Configuration;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

// В bitrix/.settings.php задал параметр iblock_id_testovoe
$sectionsController = new IblockSectionsController(Configuration::getValue('iblock_id_testovoe'));

echo '<pre>';
print_r(
    json_encode(
        $sectionsController->getSectionsInfo(),
        JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE
    )
);
echo '</pre>';
