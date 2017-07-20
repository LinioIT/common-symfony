<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

trait FormAware
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    protected function getFormFactory(): FormFactory
    {
        return $this->formFactory;
    }

    public function setFormFactory(FormFactory $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @throws InvalidOptionsException if any given option is not applicable to the given type
     */
    public function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * @throws InvalidOptionsException if any given option is not applicable to the given type
     */
    public function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->formFactory->createBuilder(FormType::class, $data, $options);
    }
}
