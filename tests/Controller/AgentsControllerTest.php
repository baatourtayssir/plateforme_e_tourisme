<?php

namespace App\Test\Controller;

use App\Entity\Agents;
use App\Repository\AgentsRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AgentsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AgentsRepository $repository;
    private string $path = '/test/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Agents::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Agent index');

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
            'agent[email]' => 'Testing',
            'agent[roles]' => 'Testing',
            'agent[password]' => 'Testing',
            'agent[lastname]' => 'Testing',
            'agent[numtel]' => 'Testing',
            'agent[adresse]' => 'Testing',
            'agent[nomAgence]' => 'Testing',
        ]);

        self::assertResponseRedirects('/test/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Agents();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setLastname('My Title');
        $fixture->setNumtel('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNomAgence('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Agent');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Agents();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setLastname('My Title');
        $fixture->setNumtel('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNomAgence('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'agent[email]' => 'Something New',
            'agent[roles]' => 'Something New',
            'agent[password]' => 'Something New',
            'agent[lastname]' => 'Something New',
            'agent[numtel]' => 'Something New',
            'agent[adresse]' => 'Something New',
            'agent[nomAgence]' => 'Something New',
        ]);

        self::assertResponseRedirects('/test/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getLastname());
        self::assertSame('Something New', $fixture[0]->getNumtel());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getNomAgence());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Agents();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setLastname('My Title');
        $fixture->setNumtel('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNomAgence('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/test/');
    }
}
