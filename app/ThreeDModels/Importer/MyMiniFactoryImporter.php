<?php

namespace App\ThreeDModels\Importer;

class MyMiniFactoryImporter extends BaseImporter {
    public function __construct() {
        if (!config("importer.my_mini_factory.enabled")) {
            echo "Disabled";
        }
    }

    // TODO
}
