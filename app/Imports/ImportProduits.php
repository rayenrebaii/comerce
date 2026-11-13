<?php

namespace App\Imports;

use App\Models\Product;

class ImportProduits
{
    public function import($filePath)
    {
        // Initialiser un compteur pour les produits importés
        $importedCount = 0;

        // Ouvrir le fichier CSV en mode lecture
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Lire chaque ligne du fichier CSV
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Ignore les lignes vides
                if (empty($data[0])) {
                    continue;
                }

                // Crée une nouvelle instance de Product
                Product::create([
                    'product_name' => $data[0],
                    'product_description' => $data[1],
                    'product_price' => $data[2],
                    'quantity_available' => $data[3],
                    'product_img' => $data[4],
                    'discount' => $data[5],
                ]);

                // Incrémenter le compteur
                $importedCount++;
            }
            // Fermer le fichier
            fclose($handle);
        }

        // Retourner ou afficher le nombre de produits importés
        return "Import complete : $importedCount product(s) successfully imported.";
    }
}
