<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\EventListener;

use Mblunck\CozyBackend\Attributes\TrackExport;
use Mblunck\CozyBackend\Queue\Message\ExportMessage;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use TYPO3\CMS\Extbase\Event\Mvc\BeforeActionCallEvent;

readonly class TrackingInterceptor
{
    public function __construct(
        protected MessageBusInterface $messageBus
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws ExceptionInterface
     */
    public function __invoke(BeforeActionCallEvent $event): void
    {
        $controllerClassName = $event->getControllerClassName();
        $actionName = $event->getActionMethodName();

        $reflection = new ReflectionMethod($controllerClassName, $actionName);

        /** @var \ReflectionAttribute<TrackExport> */
        $attributes = $reflection->getAttributes(TrackExport::class);
        $request = $GLOBALS['TYPO3_REQUEST'];

        foreach ($attributes as $attribute) {
            /** @var TrackExport $attributeInstance */
            $attributeInstance = $attribute->newInstance();
            $description = $attributeInstance->description;
            $frontendUser = $request->getAttribute('frontend.user');
            $userId = $frontendUser->user ? (int) $frontendUser->user['uid'] : 0;
            $referrer = $request->getServerParams()['HTTP_REFERER'] ?? 'direct';
            $this->messageBus->dispatch(new ExportMessage(
                $userId,
                $referrer,
                $description,
                new \DateTimeImmutable(),
                new \DateTimeImmutable(),
            ));
        }
    }
}
