<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\User;
use App\Factory\EntityFactory;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\ExternalApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncFromApi extends Command
{
    private EntityFactory $entityFactory;
    private ExternalApiService $externalApiService;
    private string $externalApiUrl;
    private UserRepository $userRepository;
    private PostRepository $postRepository;

    public function __construct(
        EntityFactory $entityFactory,
        ExternalApiService $externalApiService,
        string $externalApiUrl,
        UserRepository $userRepository,
        PostRepository $postRepository
    ) {
        parent::__construct();

        $this->entityFactory = $entityFactory;
        $this->externalApiService = $externalApiService;
        $this->externalApiUrl = $externalApiUrl;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    protected function configure()
    {
        $this
            ->setName('users:posts:sync')
            ->setDescription('Synchronize users and posts from external API to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        //get users from API
        $apiUsers = $this->externalApiService->makeGetRequest($this->externalApiUrl.'users');

        $io->writeln('Starting users sync');

        //save or update
        foreach ($apiUsers as $user) {
            if (empty($user['name']) || empty($user['username'])) {
                continue;
            }

            $existingUser = $this->userRepository->findOneBy(['email' => $user['email']]);

            if ($existingUser) {
                $existingUser->setName($user['name']);
                $existingUser->setUsername($user['username']);

                $this->userRepository->save($existingUser);

                $io->writeln(sprintf('User %s updated', $existingUser->getEmail()));
            } else {
                $newUserJson = json_encode($user);
                $newUser = $this->entityFactory->createFromJson((string) $newUserJson, User::class, ['user:create']);

                $this->userRepository->save($newUser);

                $io->writeln(sprintf('User %s created', $newUser->getEmail()));
            }
        }

        $io->writeln('Starting posts sync');

        //get posts from API
        $apiPosts = $this->externalApiService->makeGetRequest($this->externalApiUrl.'posts');

        //save or update
        foreach ($apiPosts as $post) {
            if (empty($post['title']) || empty($post['body'])) {
                continue;
            }

            $existingPost = $this->postRepository->findOneBy(['title' => $post['title']]);
            $user = $this->userRepository->find($post['userId']);

            if (!$user) {
                continue;
            }

            if ($existingPost) {
                $existingPost->setBody($post['body']);
                $existingPost->setUser($user);
                $this->postRepository->save($existingPost);
            } else {
                $newPostData = ['title' => $post['title'], 'body' => $post['body']];
                $newPostJson = json_encode($newPostData);
                $newPost = $this->entityFactory->createFromJson((string) $newPostJson, Post::class, ['post:create', 'post:user']);
                $newPost->setUser($user);
                $this->postRepository->save($newPost);
            }
        }

        $output->writeln('<info>Users and posts synced successfully.</info>');
    }
}
