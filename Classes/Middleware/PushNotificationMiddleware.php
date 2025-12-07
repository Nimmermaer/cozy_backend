<?php

namespace Mblunck\CozyBackend\Middleware;

use Mblunck\CozyBackend\Domain\Repository\SubscriptionRepository;
use Minishlink\WebPush\Subscription;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PushNotificationMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if(str_contains($request->getUri()->getPath(), '/api/push-subscribe')) {
            $requestBody = json_decode($request->getBody()->getContents(), true);
            $subscription = Subscription::create([
                'endpoint' => $requestBody['endpoint'],
                'keys' => [
                    'p256dh' => $requestBody['keys']['p256dh'],
                    'auth' => $requestBody['keys']['auth'],
                ],
            ]);
            if(!$this->isSubscriptionExist($subscription)) {
                /** @var SubscriptionRepository $subscriptionRepository */
                $subscriptionRepository = GeneralUtility::makeInstance(SubscriptionRepository::class);
                $subscriptionRepository->saveSubscription($subscription);
                return new JsonResponse(['status' => 'success'], 201);
            }
            return new JsonResponse(['status' => 'exists'], 201);
        }
      return $handler->handle($request);
    }

    private function isSubscriptionExist(Subscription $subscription): bool
    {
        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = GeneralUtility::makeInstance(SubscriptionRepository::class);
        $subscriptionItem = $subscriptionRepository->findOneBy(['auth' => $subscription->getAuthToken()]);
        return (bool)$subscriptionItem;
    }
}