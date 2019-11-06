<?php

namespace PharIo\Manifest;

use DOMDocument;
use PHPUnit\Framework\TestCase;

class RequiresElementTest extends TestCase
{
    /**
     * @var DOMDocument
     */
    private $dom;

    /**
     * @var RequiresElement
     */
    private $requires;

    public function testThrowsExceptionWhenGetPhpElementIsCalledButElementIsMissing()
    {
        $this->expectException(ManifestElementException::class);
        $this->requires->getPHPElement();
    }

    public function testHasExtElementsReturnsTrueWhenExtensionsAreRequired()
    {
        $this->dom->documentElement->appendChild(
            $this->dom->createElementNS('https://phar.io/xml/manifest/1.0', 'php')
        );

        $this->assertInstanceOf(PhpElement::class, $this->requires->getPHPElement());
    }

    protected function setUp()
    {
        $this->dom = new DOMDocument();
        $this->dom->loadXML('<?xml version="1.0" ?><requires xmlns="https://phar.io/xml/manifest/1.0" />');
        $this->requires = new RequiresElement($this->dom->documentElement);
    }

}
