<?php

/**
 * ModulesCore
 * 
 * Gère la configuration des modules à partir d'un fichier JSON.
 * Permet de vérifier l'état (activé/désactivé) des modules, de les activer,
 * de les désactiver et de sauvegarder la configuration.
 */
class ModulesCore extends \Prefab
{
    /**
     * Objet contenant la configuration des modules.
     *
     * @var object
     */
    private $modules;
    
    /**
     * Liste des noms des modules disponibles.
     *
     * @var array
     */
    private $module_list;
    
    /**
     * Chemin vers le fichier de configuration JSON.
     *
     * @var string
     */
    private $config_file;


    /**
     * Constructeur
     *
     * Charge la configuration des modules à partir d'un fichier JSON.
     *
     * @param string $config_file Chemin vers le fichier de configuration (par défaut "app/config/modules.json")
     * @throws Exception Si le fichier n'existe pas ou si le décodage JSON échoue.
     */
    public function __construct($config_file = "app/config/modules.json")
    {
        if (!file_exists($config_file)) {
            throw new Exception("ModulesCore : Le chemin de configuration est introuvable.\n");
        }

        $this->config_file = $config_file;
        $jsonContent = file_get_contents($config_file);
        $this->modules = json_decode($jsonContent);
        
        // Vérifie si le décodage JSON a réussi
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("ModulesCore : Erreur lors du décodage JSON : " . json_last_error_msg());
        }

        // Création de la liste des modules à partir des clés de l'objet JSON
        $this->module_list = array_keys((array)$this->modules);
    }

    /**
     * Sauvegarde la configuration actuelle des modules dans le fichier JSON.
     *
     * @return void
     * @throws Exception Si l'écriture du fichier échoue.
     */
    public function save()
    {
        if (!empty($this->modules)) {
            $json = json_encode($this->modules, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            if (file_put_contents($this->config_file, $json) === false) {
                throw new Exception("ModulesCore : erreur lors de l'écriture de la configuration");
            }
        }
    }

    /**
     * Vérifie si un module est activé.
     *
     * @param string $module_name Nom du module à vérifier.
     * @return bool True si le module est activé, false sinon.
     * @throws Exception Si le module n'existe pas dans la configuration.
     */
    public function is_enabled($module_name)
    {
        if (!in_array($module_name, $this->module_list)) {
            throw new \Exception("Le module $module_name n'existe pas");
        }
        return $this->modules->$module_name->enabled;
    }

    /**
     * Récupère la liste des modules disponibles.
     *
     * @return array Liste des noms de modules.
     */
    public function get_module_list()
    {
        return $this->module_list;
    }

    /**
     * Active un module.
     *
     * @param string $module_name Nom du module à activer.
     * @throws Exception Si le module n'existe pas dans la configuration.
     */
    public function enable($module_name)
    {
        if (!in_array($module_name, $this->module_list)) {
            throw new \Exception("Le module $module_name n'existe pas");
        }
        $this->modules->$module_name->enabled = true;
    }

    /**
     * Désactive un module.
     *
     * @param string $module_name Nom du module à désactiver.
     * @throws Exception Si le module n'existe pas dans la configuration.
     */
    public function disable($module_name)
    {
        if (!in_array($module_name, $this->module_list)) {
            throw new \Exception("Le module $module_name n'existe pas");
        }
        $this->modules->$module_name->enabled = false;
    }
}