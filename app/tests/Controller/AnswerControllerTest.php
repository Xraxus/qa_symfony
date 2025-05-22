<?php

namespace App\Tests\Controller;

use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AnswerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $answerRepository;
    private string $path = '/answer/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->answerRepository = $this->manager->getRepository(Answer::class);

        foreach ($this->answerRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Answer index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'answer[content]' => 'Testing',
            'answer[createdAt]' => 'Testing',
            'answer[isBest]' => 'Testing',
            'answer[question]' => 'Testing',
            'answer[author]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->answerRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Answer();
        $fixture->setContent('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsBest('My Title');
        $fixture->setQuestion('My Title');
        $fixture->setAuthor('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Answer');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Answer();
        $fixture->setContent('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setIsBest('Value');
        $fixture->setQuestion('Value');
        $fixture->setAuthor('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'answer[content]' => 'Something New',
            'answer[createdAt]' => 'Something New',
            'answer[isBest]' => 'Something New',
            'answer[question]' => 'Something New',
            'answer[author]' => 'Something New',
        ]);

        self::assertResponseRedirects('/answer/');

        $fixture = $this->answerRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getIsBest());
        self::assertSame('Something New', $fixture[0]->getQuestion());
        self::assertSame('Something New', $fixture[0]->getAuthor());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Answer();
        $fixture->setContent('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setIsBest('Value');
        $fixture->setQuestion('Value');
        $fixture->setAuthor('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/answer/');
        self::assertSame(0, $this->answerRepository->count([]));
    }
}
