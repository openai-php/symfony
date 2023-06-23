<?php

namespace OpenAI\Symfony\Composer;

class RegisterBundle {
    public static function registerBundle() {
        $file = 'config/bundles.php';
        $content = file_get_contents($file);

        // Ajout de la ligne pour le bundle si elle n'existe pas déjà
        $bundle = "OpenAI\\Symfony\\OpenAIBundle::class => ['all' => true],";
        if (strpos($content, $bundle) === false) {
            // Insérer la ligne juste avant le dernier ]
            $position = strrpos($content, "]");
            $content = substr_replace($content, "    ".$bundle."\n]", $position, 1);
        }

        // Sauvegarder le fichier modifié
        file_put_contents($file, $content);
    }

}
