<?php
/**
 * @file
 * Console command to populate ElasticSearch
 */

namespace App\Command\Elastica;

use App\Entity\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Populate the search index.
 */
class PopulateCommand extends Command
{
    use LockableTrait;

    private const BATCH_SIZE = 10000;

    protected static $defaultName = 'app:elastica:populate';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $totalRows = $this->getTotalRows();
        $rowsRead = 0;

        $progressBar = new ProgressBar($output, $totalRows);
        $progressBar->setFormat('debug');
        $progressBar->start();

        do {
            $query = $this->entityManager
                ->createQuery('select s from App\Entity\Search s')
                ->setFirstResult($rowsRead)
                ->setMaxResults(self::BATCH_SIZE);
            $result = $query->execute();

            // @TODO persist result to ElasticSearch

            $rowsRead += self::BATCH_SIZE;
            $progressBar->setProgress($rowsRead);

            $this->entityManager->clear(Search::class);

        } while ($rowsRead < $totalRows);

        $progressBar->finish();

        $this->release();
    }

    private function getTotalRows(): int
    {
        return $this->entityManager->createQueryBuilder('s')
            ->select('count(search.id)')
            ->from(Search::class, 'search')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
