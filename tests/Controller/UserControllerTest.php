<?php

namespace App\Test\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $repository;
    private string $path = '/user/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(User::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

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
            'user[nom]' => 'Testing',
            'user[email]' => 'Testing',
            'user[motdepasse]' => 'Testing',
            'user[specialite]' => 'Testing',
            'user[numero]' => 'Testing',
            'user[recommandation]' => 'Testing',
            'user[poids]' => 'Testing',
            'user[taille]' => 'Testing',
            'user[niveau]' => 'Testing',
            'user[role]' => 'Testing',
            'user[mailcode]' => 'Testing',
            'user[isVerified]' => 'Testing',
        ]);

        self::assertResponseRedirects('/user/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setNom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setMotdepasse('My Title');
        $fixture->setSpecialite('My Title');
        $fixture->setNumero('My Title');
        $fixture->setRecommandation('My Title');
        $fixture->setPoids('My Title');
        $fixture->setTaille('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setRole('My Title');
        $fixture->setMailcode('My Title');
        $fixture->setIsVerified('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setNom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setMotdepasse('My Title');
        $fixture->setSpecialite('My Title');
        $fixture->setNumero('My Title');
        $fixture->setRecommandation('My Title');
        $fixture->setPoids('My Title');
        $fixture->setTaille('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setRole('My Title');
        $fixture->setMailcode('My Title');
        $fixture->setIsVerified('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[nom]' => 'Something New',
            'user[email]' => 'Something New',
            'user[motdepasse]' => 'Something New',
            'user[specialite]' => 'Something New',
            'user[numero]' => 'Something New',
            'user[recommandation]' => 'Something New',
            'user[poids]' => 'Something New',
            'user[taille]' => 'Something New',
            'user[niveau]' => 'Something New',
            'user[role]' => 'Something New',
            'user[mailcode]' => 'Something New',
            'user[isVerified]' => 'Something New',
        ]);

        self::assertResponseRedirects('/user/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getMotdepasse());
        self::assertSame('Something New', $fixture[0]->getSpecialite());
        self::assertSame('Something New', $fixture[0]->getNumero());
        self::assertSame('Something New', $fixture[0]->getRecommandation());
        self::assertSame('Something New', $fixture[0]->getPoids());
        self::assertSame('Something New', $fixture[0]->getTaille());
        self::assertSame('Something New', $fixture[0]->getNiveau());
        self::assertSame('Something New', $fixture[0]->getRole());
        self::assertSame('Something New', $fixture[0]->getMailcode());
        self::assertSame('Something New', $fixture[0]->getIsVerified());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new User();
        $fixture->setNom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setMotdepasse('My Title');
        $fixture->setSpecialite('My Title');
        $fixture->setNumero('My Title');
        $fixture->setRecommandation('My Title');
        $fixture->setPoids('My Title');
        $fixture->setTaille('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setRole('My Title');
        $fixture->setMailcode('My Title');
        $fixture->setIsVerified('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/user/');
    }
}
