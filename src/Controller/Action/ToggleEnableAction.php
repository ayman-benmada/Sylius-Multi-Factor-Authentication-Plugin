<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\Controller\Action;

use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\QrCode;
use function in_array;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ToggleEnableAction extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly GoogleAuthenticatorInterface $authenticator,
        private readonly FlashBagInterface $flashBag,
        private readonly QrCodeGenerator $qrCodeGenerator,
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function toggle(Request $request, string $formType, string $view): Response|RedirectResponse
    {
        /** @var TwoFactorInterface $resource */
        $resource = $this->getUser();

        if ($resource->getMfaSecret() === null) {
            $resource->setMfaSecret($this->authenticator->generateSecret());
        }

        /** @var Form $form */
        $form = $this->formFactory->create($formType, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest($request)->isValid()) {
            $resource = $form->getData();

            $mfaCode = $form['mfaCode']->getData();

            if ($mfaCode === null) {
                $this->flashBag->add('error', $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.the_code_must_not_be_empty'));

                return new RedirectResponse($request->getRequestUri(), 302);
            }

            if (!$this->authenticator->checkCode($resource, $mfaCode)) {
                $this->flashBag->add('error', $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.the_code_is_incorrect'));

                return new RedirectResponse($request->getRequestUri(), 302);
            }

            if ($form->getClickedButton() === $form->get('disableBtn')) {
                $resource->setMfaEnabled(false);
                $resource->setMfaSecret(null);

                $this->flashBag->add('success', $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.multi_factor_authentication_has_been_successfully_disabled'));
            } else {
                $resource->setMfaEnabled(true);

                $this->flashBag->add('success', $this->translator->trans('abenmada_multi_factor_authentication_plugin.ui.multi_factor_authentication_has_been_successfully_enabled'));
            }

            $this->entityManager->flush($resource);
        }

        return $this->render(
            $view,
            [
                'resource' => $resource,
                'form' => $form->createView(),
                'qrCode' => $this->getQrCode($resource)->writeDataUri(),
                'activateButton' => $resource->isMfaEnabled(),
            ],
        );
    }

    private function getQrCode(TwoFactorInterface $resource): QrCode
    {
        $qrCode = $this->qrCodeGenerator->getGoogleAuthenticatorQrCode($resource);
        $qrCode->setSize(180);

        return $qrCode;
    }
}
