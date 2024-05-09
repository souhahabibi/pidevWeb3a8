<?php

namespace App\Test\Controller;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MealControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MealRepository $repository;
    private string $path = '/meal/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Meal::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Meal index');

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
            'meal[name]' => 'Testing',
            'meal[imgurl]' => 'Testing',
            'meal[recipe]' => 'Testing',
            'meal[calories]' => 'Testing',
        ]);

        self::assertResponseRedirects('/meal/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Meal();
        $fixture->setName('My Title');
        $fixture->setImgurl('My Title');
        $fixture->setRecipe('My Title');
        $fixture->setCalories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Meal');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Meal();
        $fixture->setName('My Title');
        $fixture->setImgurl('My Title');
        $fixture->setRecipe('My Title');
        $fixture->setCalories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'meal[name]' => 'Something New',
            'meal[imgurl]' => 'Something New',
            'meal[recipe]' => 'Something New',
            'meal[calories]' => 'Something New',
        ]);

        self::assertResponseRedirects('/meal/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getImgurl());
        self::assertSame('Something New', $fixture[0]->getRecipe());
        self::assertSame('Something New', $fixture[0]->getCalories());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Meal();
        $fixture->setName('My Title');
        $fixture->setImgurl('My Title');
        $fixture->setRecipe('My Title');
        $fixture->setCalories('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/meal/');
    }
}
