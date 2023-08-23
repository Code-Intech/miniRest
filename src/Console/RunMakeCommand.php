<?php

namespace MiniRest\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class RunMakeCommand extends Command
{
    protected function configure()
    {
        $this->setName('make')
            ->setDescription('Create a new file')
            ->addArgument('Filename', InputArgument::REQUIRED, 'Filename');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('Filename');

        $dialogFileType = $this->getHelper('question');
        $question = new ChoiceQuestion('Deseja criar qual tipo de arquivo?', ['Controller', 'Model', 'Middleware']);

        $optionFileType = $dialogFileType->ask(
            $input,
            $output,
            $question
        );

        if ($optionFileType === 'Controller') {
            $this->makeFile($fileName,
                'src/Http/Controllers',
                $input,
                $output,
                "<?php\n\nnamespace MiniRest\Http\Controllers;\n\nuse MiniRest\Http\Request\Request;\nuse MiniRest\Http\Response\Response;\n\n\nclass {$fileName}\n{\n    // Implemente sua lógica aqui\n}\n"
            );
        }
        else if ($optionFileType === 'Model') {
            $this->makeFile($fileName,
                'src/Models',
                $input,
                $output,
                "<?php\n\nnamespace MiniRest\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\n\nclass {$fileName} extends Model\n{\n    // Implemente sua lógica aqui\n}\n"
            );
        }
        else if ($optionFileType === 'Middleware') {
            $this->makeFile($fileName,
                'src/Http/Middlewares',
                $input,
                $output,
                "<?php\n\nnamespace MiniRest\Http\Middlewares;\n\n\nclass {$fileName} implements MiddlewareInterface\n{\n    // Implemente sua lógica aqui\n}\n"
            );
        }


        return 0;
    }

    private function makeFile($fileName, $path, $input, $output, $content)
    {
        // Verifique se o modelo já existe
        if (file_exists($path . "/{$fileName}.php")) {

            $dialog = $this->getHelper('question');
            $question = new ChoiceQuestion('O arquivo já existe, deseja continuar com esta ação?', ['yes', 'no']);

            $option = $dialog->ask(
                $input,
                $output,
                $question
            );

            if ($option === 'no') {
                $output->writeln("Comando cancelado com sucesso.");
                return 0;
            }
        }

        // Crie o arquivo do modelo
        $fileContente = $content;
        file_put_contents($path . "/{$fileName}.php", $fileContente);
        $output->writeln("Arquivo '{$fileName}' criado com sucesso.");
    }
}