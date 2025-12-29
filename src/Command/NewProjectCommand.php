<?php

namespace TeensyPHP\Command;

use Hoa\Stream\Test\Unit\IStream\Out;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'new',
    description: 'Create a new project',
    help: 'This command allows you to create a new project',
    usages: ['elephant api']
)]
class NewProjectCommand
{
    public function __invoke(OutputInterface $output, #[Argument('Name of the project')]string $projectName): int
    {
        // convert to snake case
        $projectName = strtolower(
            preg_replace('/(?<!^)[A-Z]/', '_$0', $projectName),
        );
        $projectName = preg_replace('/[^a-z0-9_]/', '', $projectName);
        $projectDir = getcwd() . '/' . $projectName;

        if (file_exists($projectDir)) {
            $output->writeln( '<fg=red>Project already exists</>');;
            return Command::FAILURE;
        }

        $this->createProject($output, $projectName, $projectDir);
        $output->writeln('<fg=green>Project created successfully</>');

        return Command::SUCCESS;
    }

    /**
     * Create a new project
     * @param string $projectName
     * @param string $projectDir
     * @return void
     */
    private function createProject(
        OutputInterface $output,
        string $projectName,
        string $projectDir,
    ): void {
        // copy templates/new-teensyphp-project to projectDir
        $sourceDir = realpath(__DIR__ . '/../../templates/new-teensyphp-project');
        $targetDir = $projectDir;

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // use os copy command to copy all files including hidden files
        $uname = strtoupper(php_uname('s'));
        if (substr($uname, 0, 3) === 'WIN') {
            exec('xcopy /E /I /Y "' . $sourceDir . '" "' . $targetDir. '"');
        } elseif (substr($uname, 0, 3) === 'DAR') {
            // MacOS
            exec('cp -r ' . $sourceDir . '/* ' . $targetDir);
        } else {
            exec('cp -r ' . $sourceDir . '/* ' . $targetDir);
        }

        $hiddenFiles = ['.env.example', '.gitignore'];

        foreach ($hiddenFiles as $hiddenFile) {
            $sourceFile = $sourceDir . '/' . $hiddenFile;
            $targetFile = $targetDir . '/' . $hiddenFile;
            if (file_exists($sourceFile)) {
                copy($sourceFile, $targetFile);
            }
        }

        $email = 'nobody@example.com';
        $author = 'nobody';
        if ($this->commandExists('git')) {
            $output->writeln('found git');

            $emailArray = [];
            exec('git config user.email', $emailArray);
            $email = $emailArray[0];
            if (!empty($email)) $output->writeln('found email: ' . $email);

            $authorArray = [];
            exec('git config user.name', $authorArray);
            $author = $authorArray[0];
            if (!empty($author)) $output->writeln('found author: ' . $author);
        }

        $vendor = 'nobody';
        if ($this->commandExists('gh')) {
            $output->writeln('found gh');
            $output->writeln('fetching gh username for package name');
            exec('gh auth status', $output);
            if (strpos(implode($output), 'Logged in') !== false) {
                $outputUser = [];
                exec('gh api user', $outputUser);
                $userJson = json_decode(implode($outputUser));
                if ($userJson !== null) {
                    $vendor = $userJson->login;
                }
            }
        }

        $composerJsonPath = $targetDir . '/composer.json';

        // replace placeholders in composer.json
        $composerJson = template($targetDir . '/composer.json.php', [
            'project_dirname' => $projectName,
            'author' => $author,
            'email' => $email,
            'vendor' => $vendor,
        ]);
        file_put_contents($composerJsonPath, $composerJson);
        unlink($composerJsonPath . '.php');

        if ($this->commandExists('git')) {
            $output->writeln('Initializing git repository...');
            // store cwd
            $cwd = getcwd();
            // init git repo inside $targetDir
            chdir($targetDir);
            exec('git init');
            exec('git add -A');
            exec('git branch -M main');
            exec('git commit -m \'Initial commit\'');
            chdir($cwd);
        }

        // run composer install
        $cwd = getcwd();
        chdir($targetDir);
        exec('composer install');
        chdir($cwd);

        $this->displayFinishedMessage($output, $projectName);
        stop();
    }

    /**
     * Check if a command exists
     * @param string $command
     * @return bool
     */
    private function commandExists(string $command): bool
    {
        $whereIsCommand = PHP_OS == 'WINNT' ? 'where' : 'which';

        $process = proc_open(
            '$whereIsCommand $command',
            [
                0 => ['pipe', 'r'], //STDIN
                1 => ['pipe', 'w'], //STDOUT
                2 => ['pipe', 'w'], //STDERR
            ],
            $pipes,
        );
        if ($process !== false) {
            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);

            return $stdout != '';
        }

        return false;
    }

    /**
     * Display the finished message
     * @param string $projectName
     * @return void
     */
    private function displayFinishedMessage(OutputInterface $output, string $projectName): void
    {
        $output->writeln('');
        $output->writeln('<fg=green>Project ' . $projectName . ' has been created.</>');
        $output->writeln('<fg=cyan>To start the project, run the following command:</>');
        $output->writeln('<fg=cyan>`1. run `cd ' . $projectName .'`</>');
        $output->writeln('<fg=cyan>`2. run `composer dev``</>');
        $output->writeln('');
        $output->writeln('<fg=cyan>Open your browser and visit http://localhost:8000/</>');
        $output->writeln('');
        $output->writeln('<fg=yellow>Remember: Edit database details in .env and restart `composer dev`</>');
        $output->writeln('<fg=yellow>Note: Configure your webserver to point to `public` directory</>');
    }
}

