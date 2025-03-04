<?php


class AnnotationRoutingPlugin extends Prefab
{

    private $f3;
    private $chemin;

    public function __construct()
    {
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

    function routeFromAnnotations($controllers)
    {
        foreach ($controllers as $controllerClass) {

            try {
                $reflectionClass = new ReflectionClass($controllerClass);
            } catch (ReflectionException $e) {
                $this->f3->error(500, "Erreur avec le contrôleur $controllerClass : " . $e->getMessage());
                return;
            }


            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $docComment = $method->getDocComment();
                if ($docComment) {
                    // Capture les annotations @route et @ajax
                    preg_match_all('/@([aA]jax|[rR]oute)(?:\\("([^"]+)"\\))?/', $docComment, $matches, PREG_SET_ORDER);

                    $isAjax = false;
                    $routes = [];

                    foreach ($matches as $match) {
                        [$fullMatch, $annotationType, $routePath] = $match + [null, null, null];

                        if (strtolower($annotationType) === 'ajax') {
                            $isAjax = true;
                        } elseif (strtolower($annotationType) === 'route' && $routePath !== null) {
                            $routes[] = $routePath;
                        }
                    }

                    if ($isAjax && empty($routes)) {
                        trigger_error("L'annotation @ajax ne peut pas être utilisée seule sans @route(\"...\")", E_USER_WARNING);
                        return;
                    }


                    foreach ($routes as $route) {

                        if (!preg_match('/^(GET|POST|PUT|DELETE|OPTIONS) \\/[a-zA-Z0-9_\\/-]*$/', $route)) {
                            trigger_error("Annotation de route malformée dans $controllerClass->" . $method->getName(), E_USER_WARNING);
                            continue;
                        }

                        list($httpMethod, $path) = explode(' ', $route, 2);
                        $routeName = "$httpMethod $path" . ($isAjax ? " [ajax]" : "");
                        $controllerMethod = "$controllerClass->{$method->getName()}";
                        $this->f3->route($routeName, $controllerMethod);
                    }
                }
            }
        }
    }
}
