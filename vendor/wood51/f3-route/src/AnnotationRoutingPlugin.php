<?php


class AnnotationRoutingPlugin extends Prefab
{

    public function __construct()
    {
            $autoload = \Base::instance()->AUTOLOAD;
            $controllers = $this->getControllers($autoload);
            $this->routeFromAnnotations($controllers);
    }

    private function getControllers($autoload) {
        $controllers = [];
        $directories = explode('|', $autoload);
    
        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                throw new \Exception("Le chemin vers les contrôleurs est erroné: $directory");
            }
    
            foreach (glob($directory . '/*.php') as $file) {
                $controllers[] = pathinfo($file, PATHINFO_FILENAME);
            }
        }
    
        return $controllers;
    }

    private function routeFromAnnotations($controllers)
    {
        $f3 = \Base::instance();
        // 3 foreach imbriquer avec pas mal de code a divisé ???!!!!
        foreach ($controllers as $controllerClass) {  // for each principal 

            try {
                $reflectionClass = new ReflectionClass($controllerClass);
            } catch (ReflectionException $e) {
                trigger_error("Erreur avec le contrôleur $controllerClass : " . $e->getMessage(), E_USER_WARNING);
                return;
            }


            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) { // for each de récup type de route 
                $docComment = $method->getDocComment();
                if ($docComment) {
                    // Capture les annotations @route et @ajax
                    preg_match_all('/@([aA]jax|[sS]ync|[rR]oute)(?:\\("([^"]+)"\\))?/', $docComment, $matches, PREG_SET_ORDER);

                    $isAjax = false;
                    $isSync = false;
                    $routes = [];

                    foreach ($matches as $match) {
                        [$fullMatch, $annotationType, $routePath] = $match + [null, null, null];

                        switch (strtolower($annotationType)) {
                            case "route":
                                $routes[] = $routePath;
                                break;

                            case "ajax":
                                $isAjax = true;
                                break;

                            case "sync":
                                $isSync = true;
                                break;
                        }
                    }

                    if (($isAjax || $isSync) && empty($routes)) {
                        trigger_error("L'annotation @ajax ou @sync ne peuvent pas être utilisée seule sans @route(\"...\")", E_USER_WARNING);
                        return;
                    }

                    if (($isAjax && $isSync)) {
                        trigger_error("L'annotation @ajax ou @sync ne peuvent pas être utilisée conjointement", E_USER_WARNING);
                        return;
                    }

                    foreach ($routes as $route) { // for each pour générer la route et vérif que le "verbe" est correct

                        if (!preg_match('/^(GET|POST|PUT|DELETE|OPTIONS) \\/[a-zA-Z0-9_\\/-]*$/', $route)) {
                            trigger_error("Annotation de route malformée dans $controllerClass->" . $method->getName(), E_USER_WARNING);
                            return;
                        }

                        list($httpMethod, $path) = explode(' ', $route, 2);

                        if ($isAjax) $path .= " [ajax]";
                        if ($isSync) $path .= " [sync]";

                        $routeName = "$httpMethod $path"; // . ($isAjax ? " [ajax]" : "");
                        $controllerMethod = "$controllerClass->{$method->getName()}";
                        echo "<li>route(\"$routeName\",$controllerMethod)";
                        $f3->route($routeName, $controllerMethod);
                    }
                }
            }
        }
    }
}
