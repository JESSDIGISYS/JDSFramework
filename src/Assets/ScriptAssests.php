<?php

namespace App\Assets;

class ScriptAssets
{
    public function __construct(
        private array $cssAssets = [],
        private array $jsAssets = []
    ) {
    }

    public function setAssets(string $cssPath, string $jsPath)
    {
        // scan the directories for all the css files and load them into the appropriate asset array

        // example:

        // cssAssets[foundation] = cssPath . /foundation.min.css
        // cssAssets[app] = cssPath . /app.css

        // jsAssets[app] = jsPath . /app.js
        // jsAssets[foundation] = jsPath . /vendor/foundation.min.js
        // jsAssets[jquery] = jsPath . /vendor/jquery.js
        // jsAssets[what-input] = jsPath . /vendor/what-input.js
        $cssFiles = scandir($cssPath);
        $jsFiles = scandir($jsPath);
        $dir = '';
        foreach ($cssFiles as $key => $file) {
            // if . or .. is the file then skip them
            if (in_array($file, [".", ".."])) {
                continue;
            }
            // if it is a dir then set the dir value to the file and skip it
            if (is_dir($file)) {
                $dir = '/' . $file;
                continue;
            }
            // add the string to the appropriate asset and continue
            // if file = foundation.min.css, I want only foundation to be the asset
            $this->cssAssets[substr($file, 0, strpos($file, '.'))] = $cssPath . $dir . '/' . $file;
        }
        $jdir = '';
        foreach ($jsFiles as $key => $file) {
            if (in_array($file, [".", ".."])) {
                continue;
            }
            if (is_dir($file)) {
                $jdir = '/' . $file;
                continue;
            }
            $this->jsAssets[substr($file, 0, strpos($file, '.'))] = $jsPath . $dir . '/' . $file;
        }
    }

    /**
     * Get the value of cssAssets
     */
    public function getCssAssets()
    {
        return $this->cssAssets;
    }


        /**
         * Get the value of jsAssets
         */ 
        public function getJsAssets()
        {
                return $this->jsAssets;
        }
}
