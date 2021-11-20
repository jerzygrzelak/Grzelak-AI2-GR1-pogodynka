<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WeatherByIdCommand extends Command
{
    protected static $defaultName = 'weatherById:command';
    protected static $defaultDescription = 'Add a short description for your command';

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
            ->addArgument('arg1', InputArgument::OPTIONAL, 'City ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        if (!is_int($arg1)) {
            $io->error('You have not passed a number');
            return 1;
        }
        $arg1=intval($arg1);
        $c=$this->container->get('doctrine')->getRepository('App:City');
        $m=$this->container->get('doctrine')->getRepository('App:Measurement');
        $city=$c->find($arg1);
        if($city==null){
        $io->error('No city with such an ID found!');
        return 1;
        }
        $result= $this->weatherUtil->getWeatherByLocation($city,$m);
        $io->success($result[0]->getDescription());
        return 0;
    }
}
