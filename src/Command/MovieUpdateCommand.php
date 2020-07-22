<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Repository\MovieRepository;
use App\Manager\ApiDataMovieManager;
use Doctrine\ORM\EntityManagerInterface;

class MovieUpdateCommand extends Command
{
    protected static $defaultName = 'movie:update';

    private $em;
    private $movieRepository;
    private $ApiDataMovieManager;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, ApiDataMovieManager $ApiDataMovieManager)
    {
        parent::__construct();
        $this->em = $em;
        $this->movieRepository = $movieRepository;
        $this->ApiDataMovieManager = $ApiDataMovieManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Ajoutedes données provenant de l api "themoviedb.org" a tous les movies qui n en ont pas')
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
            //if ($movie->getPicture() == null) {
                $this->ApiDataMovieManager->setMovieDataApi($movie);
                $counter++;
            //}
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

        $io->success('Vous avez ajoutez des données pour '. $counter . ' films de votre Bdd');

        return 0;
    }
}
