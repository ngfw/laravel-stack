<?php

namespace Ngfw\LaravelStack\Installers;

use Symfony\Component\Process\Process;

class VITStackInstaller extends Installer
{
    protected string $manifestFile = '/Manifests/vit.json';

    protected string $boilerplatePath = '/Boilerplates/vue/';
    public function vuefy()
    {
        $this->output->writeln("<info>Setting up Vue.js + Tailwind CSS stack for '{$this->projectName}'...</info>");
        $projectPath = $this->getBackendDirectory();
        foreach (
            [
                'composer require inertiajs/inertia-laravel' => "Inertia Backend Installed",
                'npm install vue@latest' => "Latest version of vue.js is installed",
                'npm install @inertiajs/inertia @inertiajs/inertia-vue3' => "Inertia and inertia adapter installed",
                'npm install laravel-vite-plugin' => "Vite Vue plugin is installed",
                'npm install @vitejs/plugin-vue' => "Vite Vue plugin is installed",
                'npm install sass' => 'SASS Installer',
                'npm install sass-embedded' => 'SASS embedded installed',
                'npm install @tsparticles/confetti' => "Installing confetti package"

            ] as $command => $message
        ) {
            $this->execute($command, $message);
        }

        // Generate vite.config.ts
        $viteConfigPath = "$projectPath/vite.config.js";
        file_put_contents(
            $viteConfigPath,
            <<<EOT
        import { defineConfig } from 'vite';
        import laravel from 'laravel-vite-plugin';
        import vue from '@vitejs/plugin-vue'; 
        
        export default defineConfig({
            plugins: [
                laravel({
                    input: [
                        './resources/css/app.css',
                        './resources/js/app.js',
                        './resources/**/*.vue',
                    ],
                    refresh: true,
                }),
                vue({ 
                    template: {
                        transformAssetUrls: {
                            base: null,
                            includeAbsolute: false,
                        },
                    },
                }),
            ],
            resolve: { 
                alias: {
                    vue: 'vue/dist/vue.esm-bundler.js',
                },
            },
        });
        EOT
        );

        $boilerplatePath = realpath(dirname(__FILE__) . "/../") . "{$this->boilerplatePath}";
        $this->copyDirectory("$boilerplatePath/resources", "$projectPath/resources");
        $this->copyDirectory("$boilerplatePath/routes", "$projectPath/routes");

        $this->output->writeln("<info>✓ Vue.js setup completed</info>");
        return true;
    }


    protected function execute($command, $message = null)
    {
        $this->output->writeln("<info>→  Executing: {$command}</info>");
        $projectPath = $this->getBackendDirectory();
        $process = Process::fromShellCommandline("cd {$projectPath} && {$command}");
        $process->setTimeout(300);
        $process->run();
        if (!$process->isSuccessful()) {
            $this->output->writeln("<error>Error executing {$command}: {$process->getErrorOutput()}</error>");
            return false;
        }
        $this->output->writeln("<info>✓ " . ($message ? $message : 'done!') . "</info>");
        return true;
    }
}
