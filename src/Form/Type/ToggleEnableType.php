<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ToggleEnableType extends AbstractResourceType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        string $dataClass,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mfaSecret', TextType::class, [
                'label' => $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.your_authentication_secret_code'),
                'required' => true,
                'attr' => ['readonly' => true],
            ])
            ->add('mfaCode', TextType::class, [
                'label' => $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.multi_factor_authentication_code'),
                'required' => true,
                'mapped' => false,
                'help_html' => true,
                'help' => '<small>' . $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.to_enable_or_disable_multi_factor_authentication_please_download_an_authentication_app_scan_the_qr_code_enter_the_displayed_code_then_click_on_the_activation_or_deactivation_button') . '</small>',
            ])
            ->add('disableBtn', SubmitType::class, [
                'label' => $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.disable_multi_factor_authentication'),
            ]);
    }
}
