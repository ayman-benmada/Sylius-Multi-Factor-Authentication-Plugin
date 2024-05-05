<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Controller\Action\AdminUser;

use Abenmada\MultiFactorAuthenticationPlugin\Controller\Action\ToggleEnableAction as BaseToggleEnableAction;
use Abenmada\MultiFactorAuthenticationPlugin\Form\Type\AdminUser\ToggleEnableType as AdminUserToggleEnableType;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ToggleEnableAction extends BaseToggleEnableAction
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly GoogleAuthenticatorInterface $authenticator,
        private readonly FlashBagInterface $flashBag,
        private readonly QrCodeGenerator $qrCodeGenerator,
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct($this->formFactory, $this->authenticator, $this->flashBag, $this->qrCodeGenerator, $this->entityManager, $this->translator);
    }

    public function __invoke(Request $request): Response|RedirectResponse
    {
        return $this->toggle($request, AdminUserToggleEnableType::class, '@MultiFactorAuthenticationPlugin/Form/AdminUser/index.html.twig');
    }
}
