<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Processors;

use TYPO3\CMS\Core\DataHandling\ItemsProcessorContext;
use TYPO3\CMS\Core\DataHandling\ItemsProcessorInterface;
use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Schema\Struct\SelectItemCollection;
use TYPO3\CMS\Core\Schema\TcaSchemaFactory;

readonly class SortingItemsProcessor implements ItemsProcessorInterface
{
    public function __construct(
        protected TcaSchemaFactory $schemaFactory,
    ) {
    }

    public function processItems(SelectItemCollection $items, ItemsProcessorContext $context): SelectItemCollection
    {
        $sorting = [
            'Erstellungsdatum' => 'crdate',
            'Titel' => 'header',
        ];
        foreach ($sorting as $labels => $value) {
            $items->add(
                new SelectItem(
                    type: 'select',
                    label: $labels,
                    value: $value,
                )
            );
        }
        return $items;
    }
}
