<?php

declare(strict_types=1);

/*
 * This file is part of the package mblunck/cozy-backend.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */
namespace Mblunck\CozyBackend\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
final class Subscription extends AbstractEntity
{
    protected string $auth;
    protected string $authToken;
    protected string $contentEncoding;
    protected string $endpoint;
    protected string $publicKey;

    protected string $p256dh;
    public function getAuth(): string
    {
        return $this->auth;
    }
    public function setAuth(string $auth): self
    {
        $this->auth = $auth;
        return $this;
    }
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }
    public function getP256dh(): string
    {
        return $this->p256dh;
    }
    public function setP256dh(string $p256dh): self
    {
        $this->p256dh = $p256dh;
        return $this;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function setAuthToken(string $authToken): self
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function getContentEncoding(): string
    {
        return $this->contentEncoding;
    }

    public function setContentEncoding(string $contentEncoding): self
    {
        $this->contentEncoding = $contentEncoding;
        return $this;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;
        return $this;
    }


}