<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\ViewHelper\Link;

use Override;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper to render the T3-Backend Contextual Record Edit Trigger.
 *
 * Example:
 * <cozybackend:link.contextualEditRecord table="pages" uid="{page.uid}" fields="title,subtitle" class="my-btn">
 * Edit
 * </cozybackend:link.contextualEditRecord>
 */
final class ContextualEditRecordViewHelper extends AbstractTagBasedViewHelper
{
    #[Override]
    protected $tagName = 'typo3-backend-contextual-record-edit-trigger';

    public function __construct(
        private readonly UriBuilder $uriBuilder
    ) {
        parent::__construct();
    }

    #[Override]
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('uid', 'int', 'UID of the record', true);
        $this->registerArgument('table', 'string', 'Database table', true);
        $this->registerArgument('fields', 'string', 'Comma separated list of fields to edit');
        $this->registerArgument('returnUrl', 'string', 'Return URL after saving', false, '');
    }

    #[Override]
    public function render(): string
    {
        $uid = (int) $this->arguments['uid'];
        $table = (string) $this->arguments['table'];
        $fields = (string) $this->arguments['fields'];

        $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);

        $returnUrl = $this->arguments['returnUrl'];
        if (empty($returnUrl) && $request instanceof ServerRequestInterface) {
            $returnUrl = (string) $request->getAttribute('normalizedParams')?->getRequestUri();
        }

        $baseParams = [
            'edit' => [
                $table => [
                    $uid => 'edit',
                ],
            ],
            'returnUrl' => $returnUrl,
        ];

        if ($fields !== '' && $fields !== '0') {
            $baseParams['columnsOnly'] = [
                $table => GeneralUtility::trimExplode(',', $fields, true),
            ];
        }

        $editUrl = (string) $this->uriBuilder->buildUriFromRoute('record_edit', $baseParams);

        $contextualUrl = (string) $this->uriBuilder->buildUriFromRoute('record_edit_contextual', $baseParams);

        $this->tag->addAttribute('url', $contextualUrl);
        $this->tag->addAttribute('edit-url', $editUrl);

        $this->tag->setContent((string) $this->renderChildren());
        $this->tag->forceClosingTag(true);
        return $this->tag->render();
    }
}
