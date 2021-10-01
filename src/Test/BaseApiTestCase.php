<?php

namespace App\Test;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseApiTestCase extends ApiTestCase
{
    const USER_FOR_TEST_TOKEN = '313632393933303036345f315fd37227402a8126d4980ec366400e76fa03812c6a';

    const ADMIN_ROLE = 0;
    const ROLE_WITHOUT_PERMISSIONS = 1;
    const ROLE_WITH_ACCOUNTS_AND_ACCOUNTS = 2;

    protected Client $client;

    protected Generator $faker;

    private static bool $isDatabaseLoaded = false;

    /**
     * @throws ToolsException
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        if (!self::$isDatabaseLoaded) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

            // Using entity manager to create schema tool for manipulating schema
            $schemaTool = new SchemaTool($entityManager);

            // Dropping database to restart id count
            $schemaTool->dropDatabase();
            // Creating an empty schema
            $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

            // App class allows launch bin/console commands on tests
            $app = new Application(self::createClient()->getKernel());
            // Disabling auto exit prevents tests stops
            $app->setAutoExit(false);

            // Executing fixtures load
            $app->run(new StringInput('doctrine:fixtures:load --no-interaction'));

            // Using this bool to prevent database loading after each tests.
            self::$isDatabaseLoaded = true;
        }
    }

    protected static function bootKernel(array $options = []): KernelInterface
    {
        return parent::bootKernel($options);
    }

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        $this->client = static::createClient([], [
            'headers' => [
                'X-AUTH-TOKEN' => self::USER_FOR_TEST_TOKEN,
                'X-AUTH-ROLE' => self::ADMIN_ROLE
            ]
        ]);

        // Initializing transaction to fast database recovering.
        $this->getEntityManager()->getConnection()
            ->beginTransaction();
    }

    /**
     * @throws ConnectionException
     */
    protected function tearDown(): void
    {
        // Get actual database connection
        $connection = $this->getEntityManager()->getConnection();

        if ($connection->isTransactionActive()) {
            // Rollback database to the initial state.
            $connection->rollBack();
        }
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager(): object
    {
        return self::getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function getRepository(string $entityClass): EntityRepository|ObjectRepository
    {
        return $this->getEntityManager()->getRepository($entityClass);
    }

}
