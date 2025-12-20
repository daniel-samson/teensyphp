<?php

namespace TeensyPHP\Command;

/**
 * NewProject command
 */
final class NewProject
{
    /**
     * @param array $options
     * @param array $arguments
     * @return void
     */
    public function __invoke($options, $arguments): void
    {
        if (
            isset($options["help"]) ||
            isset($options["h"]) ||
            count($arguments) < 2
        ) {
            $this->displayHelp();
            stop();
        }

        $projectName = $arguments[1];
        // convert to snake case
        $projectName = strtolower(
            preg_replace("/(?<!^)[A-Z]/", '_$0', $projectName),
        );
        $projectName = preg_replace("/[^a-z0-9_]/", "", $projectName);
        $projectDir = getcwd() . "/" . $projectName;

        if (file_exists($projectDir)) {
            echo "Project already exists\n";
            stop();
        }

        $this->createProject($projectName, $projectDir);
        echo "Project created successfully\n";

        stop();
    }

    /**
     * Display the help message
     * @return void
     */
    private function displayHelp(): void
    {
        echo "Usage: teensyphp new [options] project-name" . PHP_EOL;
        echo "Options:" . PHP_EOL;
        echo "  -h, --help\t Display this help message" . PHP_EOL;
    }

    /**
     * Create a new project
     * @param string $projectName
     * @param string $projectDir
     * @return void
     */
    private function createProject(
        string $projectName,
        string $projectDir,
    ): void {
        // copy templates/new-teensyphp-project to projectDir
        $sourceDir = __DIR__ . "/../../templates/new-teensyphp-project";
        $targetDir = $projectDir;

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // use os copy command to copy all files including hidden files
        $uname = strtoupper(php_uname("s"));
        if (substr($uname, 0, 3) === "WIN") {
            exec("xcopy /E /I /Y " . $sourceDir . " " . $targetDir);
            return;
        } elseif (substr($uname, 0, 3) === "DAR") {
            // MacOS
            exec("cp -r " . $sourceDir . "/* " . $targetDir);
        } else {
            exec("cp -r " . $sourceDir . "/* " . $targetDir);
        }

        $hiddenFiles = [".env.example", ".gitignore"];

        foreach ($hiddenFiles as $hiddenFile) {
            $sourceFile = $sourceDir . "/" . $hiddenFile;
            $targetFile = $targetDir . "/" . $hiddenFile;
            if (file_exists($sourceFile)) {
                copy($sourceFile, $targetFile);
            }
        }

        $email = "nobody@example.com";
        $author = "nobody";
        if ($this->commandExists("git")) {
            exec("git config user.email", $emailArray);
            $email = $emailArray[0];
            exec("git config user.name", $authorArray);
            $author = $authorArray[0];
        }

        $vendor = "nobody";
        if ($this->commandExists("gh")) {
            exec("gh auth status", $output);
            if (strpos(implode($output), "Logged in") !== false) {
                exec("gh api user", $outputUser);
                $userJson = json_decode(implode($outputUser));
                if ($userJson !== null) {
                    $vendor = $userJson->login;
                }
            }
        }

        $composerJsonPath = $targetDir . "/composer.json";

        // replace placeholders in composer.json
        $composerJson = template($targetDir . "/composer.json.php", [
            "project_dirname" => $projectName,
            "author" => $author,
            "email" => $email,
            "vendor" => $vendor,
        ]);
        file_put_contents($composerJsonPath, $composerJson);
        unlink($composerJsonPath . ".php");

        if ($this->commandExists("git")) {
            echo "Initializing git repository..." . PHP_EOL;
            // store cwd
            $cwd = getcwd();
            // init git repo inside $targetDir
            chdir($targetDir);
            exec("git init");
            exec("git add -A");
            exec("git branch -M main");
            exec("git commit -m 'Initial commit'");
            chdir($cwd);
        }

        // run composer install
        $cwd = getcwd();
        chdir($targetDir);
        exec("composer install");
        chdir($cwd);

        $this->displayFinishedMessage($projectName);
        stop();
    }

    /**
     * Check if a command exists
     * @param string $command
     * @return bool
     */
    private function commandExists(string $command): bool
    {
        $whereIsCommand = PHP_OS == "WINNT" ? "where" : "which";

        $process = proc_open(
            "$whereIsCommand $command",
            [
                0 => ["pipe", "r"], //STDIN
                1 => ["pipe", "w"], //STDOUT
                2 => ["pipe", "w"], //STDERR
            ],
            $pipes,
        );
        if ($process !== false) {
            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);

            return $stdout != "";
        }

        return false;
    }

    /**
     * Display the finished message
     * @param string $projectName
     * @return void
     */
    private function displayFinishedMessage(string $projectName): void
    {
        echo "Project created successfully" . PHP_EOL;
        echo "To start the project, run the following command:" . PHP_EOL;
        echo "1. run `cd $projectName`" . PHP_EOL;
        echo "2. run `composer install`" . PHP_EOL;
        echo "3. Copy .env.example to .env" . PHP_EOL;
        echo "configure your webserver to point to `public` directory" .
            PHP_EOL;
        echo "4. open your browser and visit http://localhost:<port number>/" .
            PHP_EOL;
        echo "Have fun!" . PHP_EOL;
    }
}
