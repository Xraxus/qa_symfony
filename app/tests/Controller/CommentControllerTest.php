<?php

namespace App\Tests\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CommentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $commentRepository;
    private string $path = '/comment/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->commentRepository = $this->manager->getRepository(Comment::class);

        foreach ($this->commentRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Comment index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'comment[content]' => 'Testing',
            'comment[email]' => 'Testing',
            'comment[nickname]' => 'Testing',
            'comment[createdAt]' => 'Testing',
            'comment[question]' => 'Testing',
            'comment[author]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->commentRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Comment();
        $fixture->setContent('My Title');
        $fixture->setEmail('My Title');
        $fixture->setNickname('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setQuestion('My Title');
        $fixture->setAuthor('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Comment');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Comment();
        $fixture->setContent('Value');
        $fixture->setEmail('Value');
        $fixture->setNickname('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setQuestion('Value');
        $fixture->setAuthor('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'comment[content]' => 'Something New',
            'comment[email]' => 'Something New',
            'comment[nickname]' => 'Something New',
            'comment[createdAt]' => 'Something New',
            'comment[question]' => 'Something New',
            'comment[author]' => 'Something New',
        ]);

        self::assertResponseRedirects('/comment/');

        $fixture = $this->commentRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getNickname());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getQuestion());
        self::assertSame('Something New', $fixture[0]->getAuthor());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Comment();
        $fixture->setContent('Value');
        $fixture->setEmail('Value');
        $fixture->setNickname('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setQuestion('Value');
        $fixture->setAuthor('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/comment/');
        self::assertSame(0, $this->commentRepository->count([]));
    }
}
