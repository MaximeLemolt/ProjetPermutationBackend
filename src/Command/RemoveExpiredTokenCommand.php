<?php

namespace App\Command;

use App\Repository\TokenBlacklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveExpiredTokenCommand extends Command
{
    protected static $defaultName = 'app:token:remove-expired';

    private $tokenBlacklistRepository;

    private $em;

    public function __construct(TokenBlacklistRepository $tokenBlacklistRepository, EntityManagerInterface $em)
    {
        $this->tokenBlacklistRepository = $tokenBlacklistRepository;
        $this->em = $em;
        // On  doit appeller le constructeur du parent qui contient du code si non exécuté => bug
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Remove the blacklisted token if it has expired')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $nbTokens = $this->tokenBlacklistRepository->deleteExpiratedToken();

        $this->em->flush();

        if ($nbTokens > 1) {
            $io->success($nbTokens.' tokens removed.');
        } else {
            $io->success($nbTokens.' token removed.');
        }
        
        return 0;
    }
}
