<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Form\Type\AdminUser;

use Abenmada\MultiFactorAuthenticationPlugin\Form\Type\ToggleEnableType as BaseToggleEnableType;
use Symfony\Contracts\Translation\TranslatorInterface;

class ToggleEnableType extends BaseToggleEnableType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        string $dataClass,
        array $validationGroups = [],
    ) {
        parent::__construct($this->translator, $dataClass, $validationGroups);
    }

    public function getBlockPrefix(): string
    {
        return 'abenmada_multi_factor_authentication_plugin_admin_user_type';
    }
}
