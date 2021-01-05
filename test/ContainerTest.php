<?php

namespace MyDI\Test;

use MyDI\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * 简单实例化.
     *
     * @throws \MyDI\MyDIException
     * @throws \ReflectionException
     */
    public function testSimple()
    {
        // 实例化容器，需要两个参数
        // 1. 类名
        // 2. 实例化类所依赖的参数，或者嵌套依赖的参数，参数名必须与数组键名对齐
        $container = new Container(TestClass1::class);
        $res       = $container->get();
        $this->assertEquals($res->getValue(), 'value');
    }

    /**
     * 注入常量参数.
     *
     * @throws \MyDI\MyDIException
     * @throws \ReflectionException
     */
    public function testValue()
    {
        // 实例化容器，需要两个参数
        // 1. 类名
        // 2. 实例化类所依赖的参数，或者嵌套依赖的参数，参数名必须与数组键名对齐
        $return    = 'value2';
        $container = new Container(TestClass2::class, ['param' => $return]);
        $res       = $container->get();
        $this->assertEquals($res->getValue(), $return);
    }

    /**
     * 注入类.
     *
     * @throws \MyDI\MyDIException
     * @throws \ReflectionException
     */
    public function testClass()
    {
        // 实例化容器，需要两个参数
        // 1. 类名
        // 2. 实例化类所依赖的参数，或者嵌套依赖的参数，参数名必须与数组键名对齐
        $return    = 'beconfident666@gmail.com';
        $container = new Container(TestClass3::class, ['email' => $return]);
        $res       = $container->get();
        $this->assertEquals($res->getValue(), $return);
    }

    /**
     * 注入类（嵌套）.
     *
     * @throws \MyDI\MyDIException
     * @throws \ReflectionException
     */
    public function testNestingClass()
    {
        // 实例化容器，需要两个参数
        // 1. 类名
        // 2. 实例化类所依赖的参数，或者嵌套依赖的参数，参数名必须与数组键名对齐
        $return    = 'beconfident666@gmail.com';
        $container = new Container(TestClass4::class, ['service' => ['email' => $return]]);
        $res       = $container->get();
        $this->assertEquals($res->getValue(), $return);
    }
}

class TestClass1
{
    public function getValue()
    {
        return 'value';
    }
}

class TestClass2
{
    private $p;

    public function __construct($param)
    {
        $this->p = $param;
    }

    public function getValue()
    {
        return $this->p;
    }
}

class TestClass3
{
    private $p;

    public function __construct(EmailService1 $service, $email)
    {
        $service->setEmail($email);
        $this->p = $service->getEmail();
    }

    public function getValue()
    {
        return $this->p;
    }
}

class TestClass4
{
    private $p;

    public function __construct(EmailService2 $service)
    {
        $this->p = $service->getEmail();
    }

    public function getValue()
    {
        return $this->p;
    }
}

class EmailService2
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}

class EmailService1
{
    private $email;

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
