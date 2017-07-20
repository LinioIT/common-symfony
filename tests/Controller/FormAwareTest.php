<?php

declare(strict_types=1);

namespace Linio\Common\Symfony\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;

class FormAwareTest extends TestCase
{
    public function testIsSettingFormFactory()
    {
        $formFactory = $this->prophesize(FormFactoryInterface::class);

        $controller = new class {
            use FormAware;

            public function test()
            {
                return $this->getFormFactory();
            }
        };
        $controller->setFormFactory($formFactory->reveal());

        $actual = $controller->test();

        $this->assertInstanceOf(FormFactoryInterface::class, $actual);
    }

    public function testIsCreatingForm()
    {
        $form = $this->prophesize(FormInterface::class);

        $formFactory = $this->prophesize(FormFactoryInterface::class);
        $formFactory->create(FormType::class, ['initial_data' => 'initial_data'], ['options' => 'options'])
            ->willReturn($form->reveal());

        $controller = new class {
            use FormAware;

            public function test($type, $data, $options)
            {
                return $this->createForm($type, $data, $options);
            }
        };
        $controller->setFormFactory($formFactory->reveal());

        $actual = $controller->test(FormType::class, ['initial_data' => 'initial_data'], ['options' => 'options']);

        $this->assertInstanceOf(FormInterface::class, $actual);
    }

    public function testIsCreatingFormBuilder()
    {
        $formBuilder = $this->prophesize(FormBuilderInterface::class);

        $formFactory = $this->prophesize(FormFactoryInterface::class);
        $formFactory->createBuilder(FormType::class, ['initial_data' => 'initial_data'], ['options' => 'options'])
            ->willReturn($formBuilder->reveal());

        $controller = new class {
            use FormAware;

            public function test($data, $options)
            {
                return $this->createFormBuilder($data, $options);
            }
        };
        $controller->setFormFactory($formFactory->reveal());

        $actual = $controller->test(['initial_data' => 'initial_data'], ['options' => 'options']);

        $this->assertInstanceOf(FormBuilderInterface::class, $actual);
    }
}
