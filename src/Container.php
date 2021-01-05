<?php

namespace MyDI;

class Container
{
    /**
     * 类名.
     *
     * @var string
     */
    private $className;

    /**
     * 用户注入的自定义参数.
     *
     * @var array
     */
    private $config;

    /**
     * Container constructor.
     *
     * @param string $className 类名
     * @param array  $config    注入参数
     *
     * @throws MyDIException
     */
    public function __construct(string $className, array $config = [])
    {
        if (!class_exists($className)) {
            throw new MyDIException('Entry Is Not A Class!');
        }
        $this->className = $className;
        $this->config    = $config;
    }

    /**
     * 实例化类.
     *
     * @param \ReflectionClass $refClass  类的反射
     * @param array            $cusParams 注入参数
     *
     * @throws MyDIException
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function instanceClass(\ReflectionClass $refClass, array $cusParams = [])
    {
        // 没有方法则直接实例化
        $className = $refClass->getName();

        if (!$refClass->hasMethod('__construct')) {
            return new $className();
        }

        // 反射方法
        $theMethod = $refClass->getMethod('__construct');
        // 反射方法参数
        $params       = $theMethod->getParameters();
        $injectParams = [];

        foreach ($params as $k => $param) {
            $paramName    = $param->getName();
            $cusParamName = $paramName;
            $paramClass   = $param->getClass();

            // 注入用户自定义的参数
            if (
                isset($cusParams[$cusParamName]) &&
                (($paramClass && is_object($cusParams[$paramName])) || !$paramClass)
            ) {
                $injectParams[] = $cusParams[$param->getName()];
                continue;
            }

            // 有默认值无需注入
            if ($param->isDefaultValueAvailable()) {
                continue;
            }

            // 自动装配注入 autoWiring
            // 1. 依赖其他类
            if ($paramClass) {
                $childRefClass  = new \ReflectionClass($paramClass->getName());
                $injectParams[] = $this->instanceClass($childRefClass, isset($cusParams[$cusParamName]) ? $cusParams[$paramName] : []);
            } else {
                throw new MyDIException('Param{' . $paramName . '}Can Not Inject Automatically!');
            }
        }

        return new $className(...$injectParams);
    }

    /**
     * @throws MyDIException
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function get()
    {
        $refClass = new \ReflectionClass($this->className);

        return $this->instanceClass($refClass, $this->config);
    }
}
