<?php

namespace phpMyFAQ\Instance;

use phpMyFAQ\Configuration;
use phpMyFAQ\Instance;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    private Main $main;
    private Instance $instance;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $configuration = $this->createMock(Configuration::class);
        $this->main = new Main($configuration);
        $this->instance = $this->createMock(Instance::class);
    }

    public function testCreateMain(): void
    {
        $instanceId = 123;
        $this->instance->method('getId')->willReturn($instanceId);

        $this->main->createMain($this->instance);

        $this->assertSame($instanceId, $this->main->getId());
    }
}
