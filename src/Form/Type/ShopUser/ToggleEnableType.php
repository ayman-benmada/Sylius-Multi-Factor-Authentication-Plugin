<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Form\Type\ShopUser;

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
        return 'abenmada_multi_factor_authentication_plugin_shop_user_type';
    }
}
