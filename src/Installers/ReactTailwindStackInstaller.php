<?php

namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;
use Ngfw\LaravelStack\Helpers\ArrayHelper;

class ReactTailwindStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/react.json';
    protected string $boilerplatePath = '/Boilerplates/react/';
    public function reactify()
    {
        $projectPath = $this->getBackendDirectory();

        // Generate vite.config.ts
        $viteConfigPath = "$projectPath/vite.config.js";
        file_put_contents(
            $viteConfigPath,
            <<<EOT
        import { defineConfig } from 'vite';
        import laravel from 'laravel-vite-plugin';
        import react from '@vitejs/plugin-react';
        import path from "path";

        export default defineConfig({
            plugins: [
                laravel({
                    input: ['resources/css/app.css', 'resources/js/app.js', 'resources/react/App.tsx'],
                    refresh: true,
                }),
                react(),
            ],
            resolve: {
                alias: {
                    '@/': `\${path.resolve(__dirname, './resources/react')}/`,
                    '~/': `\${path.resolve(__dirname, './public')}/`,
                }
            },
        });
        EOT
        );

        $tsconfigPath = "$projectPath/tsconfig.json";
        $newTsConfig = [
            'compilerOptions' => [
                'allowJs' => true,
                'module' => 'ESNext',
                'moduleResolution' => 'bundler',
                'jsx' => 'react-jsx',
                'strict' => true,
                'isolatedModules' => true,
                'target' => 'ESNext',
                'esModuleInterop' => true,
                'forceConsistentCasingInFileNames' => true,
                'noEmit' => true,
                'paths' => [
                    '@/*' => ['./resources/react/*'],
                ],
            ],
            'include' => [
                'resources/react/**/*.ts',
                'resources/react/**/*.tsx',
                'resources/react/**/*.d.ts',
            ],
        ];

        // Read existing tsconfig.json if it exists
        if (file_exists($tsconfigPath)) {
            $existingTsConfig = json_decode(file_get_contents($tsconfigPath), true) ?? [];
            $mergedTsConfig = ArrayHelper::merge($existingTsConfig, $newTsConfig);
        } else {
            $mergedTsConfig = $newTsConfig;
        }

        // Write merged configuration
        file_put_contents($tsconfigPath, json_encode($mergedTsConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


        $packageJsonPath = "$projectPath/package.json";
        $newPackageJson = [
            'private' => true,
            'type' => 'module',
            'scripts' => [
                'start' => 'vite',
                'test:ts' => 'tsc -w',
                'build' => 'vite build',
            ],
            'devDependencies' => [
                '@inertiajs/react' => '^1.2.0',
                '@types/react' => '^18.3.0',
                '@types/react-dom' => '^18.3.0',
                '@vitejs/plugin-react' => '^4.3.4',
                'autoprefixer' => '^10.4.20',
                'axios' => '^1.7.9',
                'laravel-vite-plugin' => '^1.1.1',
                'path' => '^0.12.7',
                'postcss' => '^8.4.31',
                'react' => '^18.3.0',
                'react-dom' => '^18.3.0',
                'sass' => '^1.82.0',
                'tailwindcss' => '^3.4.16',
                'typescript' => '^5.7.2',
                'vite' => '^6.0.3',
                'ziggy-js' => '^2.4.1',
                '@tsparticles/confetti' => '^3.7.1'
            ],
        ];

        // Read existing package.json if it exists
        if (file_exists($packageJsonPath)) {
            $existingPackageJson = json_decode(file_get_contents($packageJsonPath), true) ?? [];
            $mergedPackageJson = ArrayHelper::merge($existingPackageJson, $newPackageJson);
        } else {
            $mergedPackageJson = $newPackageJson;
        }

        // Write merged package.json
        file_put_contents($packageJsonPath, json_encode($mergedPackageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $boilerplatePath = realpath(dirname(__FILE__) . "/../") . "{$this->boilerplatePath}";
        $this->copyDirectory("$boilerplatePath/resources", "$projectPath/resources");
        $this->copyDirectory("$boilerplatePath/routes", "$projectPath/routes");
        return true;
    }
}
