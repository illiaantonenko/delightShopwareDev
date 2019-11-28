<?php

namespace RevanCronJobs\Commands;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ShopwareCommand
{
    protected function configure()
    {

        $this
            ->setName('revancronjobs:cleardir')
            ->setDescription('Clears given directory.')
            ->addArgument(
                'dirpath',
                InputArgument::REQUIRED,
                'Path to directory to delete data from.'
            )
            ->setHelp(<<<EOF
The <info>%command.name%</info> deletes data from a directory.
EOF
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dirPath = realpath($input->getArgument('dirpath'));
        $dirs = explode('/', $dirPath);
        $dir = end($dirs);

        if (strtolower(substr($dir, 0, 9)) == 'itdelight') {
            if($this->delete($dirPath)){
                $output->writeln('<info>' . "All files was cleared!" . '</info>');
            }else{
                $output->writeln('<error>' . "Something went wrong wile clearing directory." . '</error>');
            }
        } else {
            $output->writeln('<error>' . 'You must set path to folder called itdelight' . '</error>');
        }


    }

    protected function delete($path)
    {
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    } else if (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        } else if ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
    }
}