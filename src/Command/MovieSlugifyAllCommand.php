<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Repository\MovieRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;

class MovieSlugifyAllCommand extends Command
{
    protected static $defaultName = 'movie:slugify-all';

    private $em;
    private $movieRepository;
    private $slugger;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, Slugger $slugger)
    {
        parent::__construct();
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->slugger = $slugger;
    }

    protected function configure()
    {
        $this
            ->setDescription('Ajoute un slug a tous les movies qui n en ont pas')
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $counter = 0;
        // On récupére tous les films
        $movies = $this->movieRepository->findAll();

        foreach($movies as $movie) {
            if ($movie->getSlug() == null) {
                $this->slugger->slugifyMovie($movie);
                $counter++;
            }
        }
        $this->em->flush();

        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

        //if ($arg1) {
            //$io->note(sprintf('You passed an argument: %s', $arg1));
        //}

        //if ($input->getOption('option1')) {
            // ...
        //}

        $io->success('Vous avez ajoutez '. $counter . ' slug a votre Bdd');

        return 0;
    }
}
