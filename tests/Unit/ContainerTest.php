<?php

namespace Tests\Unit;

use stdClass;
use Exception;
use App\Core\Container;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\Core\Container
 */
class ContainerTest extends TestCase
{
    public function test_can_set_and_get_binding()
    {
        $container = new Container();
        $container->set('stdClass', function () {
            return new stdClass();
        });
        $this->assertTrue($container->has('stdClass'));
        $instance = $container->get('stdClass');
        $this->assertInstanceOf(stdClass::class, $instance);
    }

    public function test_can_set_and_get_singleton_binding()
    {
        $container = new Container();
        $container->setOnce('stdClass', function () {
            return new stdClass();
        });
        $this->assertTrue($container->has('stdClass'));
        $instance = $container->get('stdClass');
        $this->assertSame($instance, $container->get('stdClass'));
    }

    public function test_resolve_instantiable_class()
    {
        $container = new Container();
        $instance = $container->resolve(stdClass::class);
        $this->assertInstanceOf(stdClass::class, $instance);
    }

    public function test_resolve_class_with_dependencies()
    {
        $container = new Container();
        $instance = $container->resolve(TestClass::class);
        $this->assertInstanceOf(TestClass::class, $instance);
        $this->assertInstanceOf(stdClass::class, $instance->getDependency());
    }

    public function test_resolve_class_with_missing_type_hint()
    {
        $container = new Container();
        $this->expectException(Exception::class);
        $container->resolve(TestClassWithMissingTypeHint::class);
    }

    public function test_resolve_class_with_union_type_hint()
    {
        $container = new Container();
        $this->expectException(Exception::class);
        $container->resolve(TestClassWithUnionTypeHint::class);
    }

    public function test_resolve_class_with_invalid_built_in_type_hint()
    {
        $container = new Container();
        $this->expectException(Exception::class);
        $container->resolve(TestClassWithInvalidBuiltInTypeHint::class);
    }

    public function test_resolve_class_with_default_value_for_built_in_type_hint()
    {
        $container = new Container();
        $instance = $container->resolve(TestClassWithDefaultValueForBuiltInTypeHint::class);
        $this->assertInstanceOf(TestClassWithDefaultValueForBuiltInTypeHint::class, $instance);
    }
}

class TestClass
{
    private $dependency;

    public function __construct(stdClass $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency()
    {
        return $this->dependency;
    }
}

class TestClassWithMissingTypeHint
{
    public function __construct($dependency)
    {
    }
}

class TestClassWithUnionTypeHint
{
    public function __construct(stdClass|int $dependency)
    {
    }
}

class TestClassWithInvalidBuiltInTypeHint
{
    public function __construct(array $dependency)
    {
    }
}

class TestClassWithDefaultValueForBuiltInTypeHint
{
    public function __construct(string $dependency = 'test')
    {
    }
}

