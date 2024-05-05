<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Model;

use Doctrine\ORM\Mapping\Column;

trait MultiFactorAuthenticationTrait
{
    /**
     * @Column(name="mfa_secret", type="string", length=255, nullable=true)
     */
    #[Column(name: 'mfa_secret', type: 'string', length: 255, nullable: true)]
    private ?string $mfaSecret = null;

    /**
     * @Column(name="mfa_enabled", type="boolean", options={"default": false})
     */
    #[Column(name: 'mfa_enabled', type: 'boolean', options: ['default' => false])]
    private bool $mfaEnabled = false;

    public function getMfaSecret(): ?string
    {
        return $this->mfaSecret;
    }

    public function setMfaSecret(?string $mfaSecret): void
    {
        $this->mfaSecret = $mfaSecret;
    }

    public function isMfaEnabled(): bool
    {
        return $this->mfaEnabled;
    }

    public function setMfaEnabled(bool $mfaEnabled): void
    {
        $this->mfaEnabled = $mfaEnabled;
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->mfaEnabled;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->mfaSecret;
    }
}
