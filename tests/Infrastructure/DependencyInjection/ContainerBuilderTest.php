<?php

namespace App\Tests\Infrastructure\DependencyInjection;

use App\Infrastructure\Attribute\ClassAttributeResolver;
use App\Infrastructure\DependencyInjection\CompilerPass;
use App\Infrastructure\DependencyInjection\ContainerBuilder;
use App\Infrastructure\Environment\Settings;
use DI\CompiledContainer;
use DI\Container;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContainerBuilderTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private MockObject $DIContainerBuilder;
    private MockObject $classAttributeResolver;

    public function testBuild(): void
    {
        $compilerPass = $this->createMock(CompilerPass::class);

        $this->DIContainerBuilder
            ->expects($this->exactly(2))
            ->method('addDefinitions')
            ->withConsecutive(
                ['definition'],
                [require Settings::getAppRoot().'/config/auto-wires.php']
            );

        $this->DIContainerBuilder
            ->expects($this->once())
            ->method('enableCompilation')
            ->with(
                'dir',
                'CompiledContainer',
                CompiledContainer::class
            );

        $compilerPass
            ->expects($this->once())
            ->method('process')
            ->with($this->containerBuilder);

        $this->DIContainerBuilder
            ->expects($this->once())
            ->method('isCompilationEnabled')
            ->willReturn(true);

        $this->DIContainerBuilder
            ->expects($this->once())
            ->method('build')
            ->willReturn($this->createMock(Container::class));

        $this->containerBuilder
            ->enableCompilation('dir')
            ->enableClassAttributeCache('dir')
            ->addDefinitions('definition')
            ->addCompilerPasses($compilerPass)
            ->build();
    }

    public function testBuildWithoutCache(): void
    {
        $compilerPass = $this->createMock(CompilerPass::class);

        $this->DIContainerBuilder
            ->expects($this->once())
            ->method('addDefinitions')
            ->with('definition');

        $compilerPass
            ->expects($this->once())
            ->method('process')
            ->with($this->containerBuilder);

        $this->DIContainerBuilder
            ->expects($this->once())
            ->method('isCompilationEnabled')
            ->willReturn(false);

        $this->DIContainerBuilder
            ->expects($this->once())
            ->method('build')
            ->willReturn($this->createMock(Container::class));

        $this->containerBuilder
            ->addDefinitions('definition')
            ->addCompilerPasses($compilerPass)
            ->build();
    }

    public function testItShouldThrowOnDuplicateCompilerPasses(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('CompilerPass CompilerPassOne already added. Cannot add the same pass twice');

        $compilerPass = $this->getMockBuilder(CompilerPass::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMockClassName('CompilerPassOne')
            ->getMock();

        $this->containerBuilder->addCompilerPasses(
            $compilerPass,
            $compilerPass
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->DIContainerBuilder = $this->createMock(\DI\ContainerBuilder::class);
        $this->classAttributeResolver = $this->createMock(ClassAttributeResolver::class);

        $this->containerBuilder = new ContainerBuilder(
            $this->DIContainerBuilder,
            $this->classAttributeResolver
        );
    }
}
