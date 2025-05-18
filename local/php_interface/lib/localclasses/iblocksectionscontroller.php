<?php

namespace LocalClasses;

use Bitrix\Main\Loader,
    Bitrix\Iblock\SectionTable;

class IblockSectionsController
{
    private int $iblockId;

    public function __construct(int $iblockId = 0)
    {
        try {
            if (!Loader::includeModule('iblock')) {
                throw new \Exception('Module iblock is not exist');
            }
            if (!$iblockId) {
                throw new \Exception('iblockId not passed');
            } else {
                $this->iblockId = $iblockId;
            }
        } catch (\Throwable $t) {
            die($t->getMessage());
        }
    }

    /**
     * Основной метод класса,
     * возвращает все разделы инфоблока с количеством элементов
     * @return array
     */
    public function getSectionsInfo(): array
    {
        // В задании не говорится про активные разделы/элементы, но если нужны именно активные,
        // то нужно раскомментировать фильтр по активности

        $res = SectionTable::getList(
            [
                'filter' =>  [
                    'IBLOCK_ID' => $this->iblockId,
//                    'ACTIVE' => 'Y',
                ],
                'select' => ['ID', 'NAME', 'ELEMENT_COUNT'],
                // сделал подсчёт элементов через join
                'runtime' => [
                    'ELEMENTS' => [
                        'data_type' => '\Bitrix\Iblock\ElementTable',
                        'reference' => [
                            '=this.IBLOCK_ID' => 'ref.IBLOCK_ID',
                            '=this.ID' => 'ref.IBLOCK_SECTION_ID',
//                            '=this.ACTIVE' => 'ref.ACTIVE',
                        ],
                    ],
                    'ELEMENT_COUNT' => [
                        'data_type' => 'integer',
                        'expression' => ['count(%s)', 'ELEMENTS.ID']
                    ]
                ],
                'cache' => ["ttl" => 3600],
            ]
        );
        $result = [];
        while ($section = $res->fetch()) {
            $result[] = $section;
        }
        return $result;
    }

}
