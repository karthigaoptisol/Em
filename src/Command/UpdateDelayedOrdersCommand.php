<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\Orders;

class UpdateDelayedOrdersCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('app:update-delayed-orders')
            ->setDescription('Find and update processing orders that have passed their delivery time to delayed.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->em->getRepository(Orders::class);

        $delayedOrders = $repository->createQueryBuilder('o')
            ->where('o.status = :status')
            ->andWhere('o.estimatedDeliveryDate < :delivery_date')
            ->setParameter('status', 'processing')
            ->setParameter('delivery_date', new \DateTime())
            ->getQuery()
            ->getResult();

        if (empty($delayedOrders)) {
            $io->success('No processing orders found that have passed their delivery time.');
            return Command::SUCCESS;
        }

        foreach ($delayedOrders as $order) {
            $order->setStatus('delayed');
            $io->success(sprintf('Order %d updated to delayed status.', $order->getId()));
        }

        $this->em->flush();

        $io->success('Update complete.');
        return Command::SUCCESS;
    }
}