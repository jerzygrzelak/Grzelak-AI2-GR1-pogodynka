<?php

namespace App\Command;



use App\Service\WeatherUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WeatherByCountryAndCityCommand extends Command
{
    protected static $defaultName = 'weatherByCountryAndCity:command';
    protected static $defaultDescription = 'Returns measurements for a given country and city combination';
    /** @var WeatherUtil */
    private $weatherUtil;
    private $container;
    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, WeatherUtil $weatherUtil)
    {
        $this->weatherUtil=$weatherUtil;
        $this->entityManager = $entityManager;
        $this->container=$container;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Country')
            ->addArgument('arg2', InputArgument::OPTIONAL, 'City')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $arg2 = $input->getArgument('arg2');
        if ($arg1==null || $arg2==null) {
            $io->error('You have not passed one or both arguments');
            return 1;
        }
        $c=$this->container->get('doctrine')->getRepository('App:City');
        $m=$this->container->get('doctrine')->getRepository('App:Measurement');
        $result=$this->weatherUtil->getWeatherForCountryAndCity($arg1,$arg2,$m,$c);
        if($result===[]){
            $io->success('No measurements found!');
            return 0;
        }
        $io->success('Measurements found!');
        $output->writeln('1: ' . $result['measurements'][0]->getDescription());
        return 0;
    }
}
