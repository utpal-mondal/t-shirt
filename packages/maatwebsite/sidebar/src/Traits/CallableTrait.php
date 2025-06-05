<?php
    use Closure;
    use Illuminate\Container\Container;
    use ReflectionFunction;

    trait CallableTrait
    {
        public function call(Closure $callback = null, $caller = null)
        {
            if ($callback instanceof Closure) {
                $reflection = new ReflectionFunction($callback);

                $container = Container::getInstance();

                $dependencies = [];
                foreach ($reflection->getParameters() as $parameter) {
                    $type = $parameter->getType();
                    if ($type && !$type->isBuiltin()) {
                        $dependencies[] = $container->make($type->getName());
                    } else {
                        $dependencies[] = null; // or fallback value
                    }
                }

                call_user_func_array($callback, $dependencies);
            }

            return $caller;
        }
    }

?>