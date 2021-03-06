<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\MediaBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author Virgile Vivier <virgilevivier@gmail.com>
 */
abstract class AbstractProviderTest extends TestCase
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * @var FormMapper
     */
    protected $formMapper;

    /**
     * @var FormTypeInterface
     */
    protected $formType;

    /**
     * @var MediaProviderInterface
     */
    protected $provider;

    protected function setUp()
    {
        // NEXT_MAJOR: Hack for php 5.3 only, remove it when requirement of PHP is >= 5.4
        $that = $this;

        $this->formMapper = $this->getMockBuilder('Sonata\AdminBundle\Form\FormMapper')->disableOriginalConstructor()->getMock();
        $this->formMapper
            ->expects($this->any())
            ->method('add')
            ->will($this->returnCallback(function ($name, $type = null) use ($that) {
                if (null !== $type) {
                    $that->assertTrue(class_exists($type), sprintf('Unable to ensure %s is a FQCN', $type));
                }
            }));

        $this->formBuilder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();
        $this->formBuilder
            ->expects($this->any())
            ->method('add')
            ->will($this->returnCallback(function ($name, $type = null) use ($that) {
                if (null !== $type) {
                    $that->assertTrue(class_exists($type), sprintf('Unable to ensure %s is a FQCN', $type));
                }
            }));

        $this->formBuilder->expects($this->any())->method('getOption')->willReturn('api');

        $this->provider = $this->getProvider();
    }

    /**
     * Get the provider which have to be tested.
     */
    abstract public function getProvider();

    public function testBuildEditForm()
    {
        $this->provider->buildEditForm($this->formMapper);
    }

    public function testBuildCreateForm()
    {
        $this->provider->buildCreateForm($this->formMapper);
    }

    public function testBuildMediaType()
    {
        $this->provider->buildMediaType($this->formBuilder);
    }
}
