<?php 


class AnnotationRoutingPlugin extends Prefab {

    private $f3;
    private $chemin;

    public function __construct($chemin = null) {
        $this->f3 = \Base::instance();

        $this->chemin = $this->f3->get('AUTOLOAD');

        $controllers = [];

        // Diviser les chemins en fonction du délimiteur '|'
        $directories = explode('|', $this->chemin);

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                $this->f3->error(500, "Le chemin vers les contrôleurs est erroné: $directory");
                return;
            }

            $files = scandir($directory);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $controllerName = pathinfo($file, PATHINFO_FILENAME);
                    $controllers[] = $controllerName;
                }
            }
        }

        $this->routeFromAnnotations($controllers);
    }

    function routeFromAnnotations($controllers) {
        foreach ($controllers as $controllerClass) {
            $reflectionClass = new ReflectionClass($controllerClass);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $docComment = $method->getDocComment();
                if ($docComment) {
                    // Capture both @ajax and @route annotations
                    preg_match_all('/@([aA]jax|[rR]oute)\("([^"]+)"\)/', $docComment, $matches, PREG_SET_ORDER);

                    $ajax = false;
                    $routes = [];

                    foreach ($matches as $match) {
                        if (strtolower($match[1]) === 'ajax') {
                            $ajax = true;
                        } elseif (strtolower($match[1]) === 'route') {
                            $routes[] = $match[2];
                        }
                    }

                    foreach ($routes as $route) {
                        list($httpMethod, $path) = explode(' ', $route, 2);
                        $routeName = "$httpMethod $path" . ($ajax ? " [ajax]" : "");
                        $controllerMethod = "$controllerClass->{$method->getName()}";
                        $this->f3->route($routeName, $controllerMethod);
                    }
                }
            }
        }
    }
}