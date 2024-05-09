<?php

namespace App\Test\Controller;

use App\Entity\Regime;
use App\Repository\RegimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegimeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RegimeRepository $repository;
    private string $path = '/regime/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Regime::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Regime index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'regime[startdate]' => 'Testing',
            'regime[enddate]' => 'Testing',
            'regime[duration]' => 'Testing',
            'regime[description]' => 'Testing',
            'regime[goal]' => 'Testing',
            'regime[clientid]' => 'Testing',
        ]);

        self::assertResponseRedirects('/regime/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Regime();
        $fixture->setStartdate('My Title');
        $fixture->setEnddate('My Title');
        $fixture->setDuration('My Title');
        $fixture->setDescription('My Title');
        $fixture->setGoal('My Title');
        $fixture->setClientid('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Regime');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Regime();
        $fixture->setStartdate('My Title');
        $fixture->setEnddate('My Title');
        $fixture->setDuration('My Title');
        $fixture->setDescription('My Title');
        $fixture->setGoal('My Title');
        $fixture->setClientid('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'regime[startdate]' => 'Something New',
            'regime[enddate]' => 'Something New',
            'regime[duration]' => 'Something New',
            'regime[description]' => 'Something New',
            'regime[goal]' => 'Something New',
            'regime[clientid]' => 'Something New',
        ]);

        self::assertResponseRedirects('/regime/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getStartdate());
        self::assertSame('Something New', $fixture[0]->getEnddate());
        self::assertSame('Something New', $fixture[0]->getDuration());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getGoal());
        self::assertSame('Something New', $fixture[0]->getClientid());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Regime();
        $fixture->setStartdate('My Title');
        $fixture->setEnddate('My Title');
        $fixture->setDuration('My Title');
        $fixture->setDescription('My Title');
        $fixture->setGoal('My Title');
        $fixture->setClientid('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/regime/');
    }
}
