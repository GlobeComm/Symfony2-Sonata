<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\MediaBundle\Tests\Security;

use PHPUnit\Framework\TestCase;
use Sonata\MediaBundle\Security\RolesDownloadStrategy;

class RolesDownloadStrategyTest extends TestCase
{
    public function testIsGrantedTrue()
    {
        $media = $this->createMock('Sonata\MediaBundle\Model\MediaInterface');
        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $translator = $this->createMock('Symfony\Component\Translation\TranslatorInterface');
        $security = $this->createMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');

        $security->expects($this->any())
            ->method('isGranted')
            ->will($this->returnCallback(function (array $roles) {
                return in_array('ROLE_ADMIN', $roles);
            }));

        $strategy = new RolesDownloadStrategy($translator, $security, ['ROLE_ADMIN']);
        $this->assertTrue($strategy->isGranted($media, $request));
    }

    public function testIsGrantedFalse()
    {
        $media = $this->createMock('Sonata\MediaBundle\Model\MediaInterface');
        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $translator = $this->createMock('Symfony\Component\Translation\TranslatorInterface');
        $security = $this->createMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');

        $security->expects($this->any())
            ->method('isGranted')
            ->will($this->returnCallback(function (array $roles) {
                return in_array('FOO', $roles);
            }));

        $strategy = new RolesDownloadStrategy($translator, $security, ['ROLE_ADMIN']);
        $this->assertFalse($strategy->isGranted($media, $request));
    }
}
