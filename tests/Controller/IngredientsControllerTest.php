<?php

namespace App\Test\Controller;

use App\Entity\Ingredients;
use App\Repository\IngredientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IngredientsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private IngredientsRepository $repository;
    private string $path = '/ingredients/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Ingredients::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ingredient index');

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
            'ingredient[name]' => 'Testing',
            'ingredient[calories]' => 'Testing',
            'ingredient[totalFat]' => 'Testing',
            'ingredient[protein]' => 'Testing',
            'ingredient[imgurl]' => 'Testing',
        ]);

        self::assertResponseRedirects('/ingredients/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ingredients();
        $fixture->setName('My Title');
        $fixture->setCalories('My Title');
        $fixture->setTotalFat('My Title');
        $fixture->setProtein('My Title');
        $fixture->setImgurl('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Ingredient');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Ingredients();
        $fixture->setName('My Title');
        $fixture->setCalories('My Title');
        $fixture->setTotalFat('My Title');
        $fixture->setProtein('My Title');
        $fixture->setImgurl('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'ingredient[name]' => 'Something New',
            'ingredient[calories]' => 'Something New',
            'ingredient[totalFat]' => 'Something New',
            'ingredient[protein]' => 'Something New',
            'ingredient[imgurl]' => 'Something New',
        ]);

        self::assertResponseRedirects('/ingredients/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getCalories());
        self::assertSame('Something New', $fixture[0]->getTotalFat());
        self::assertSame('Something New', $fixture[0]->getProtein());
        self::assertSame('Something New', $fixture[0]->getImgurl());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Ingredients();
        $fixture->setName('My Title');
        $fixture->setCalories('My Title');
        $fixture->setTotalFat('My Title');
        $fixture->setProtein('My Title');
        $fixture->setImgurl('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/ingredients/');
    }
}
