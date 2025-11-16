<?php

declare(strict_types=1);

namespace App\Front;

use App\Repository\DatabaseRepository;
use App\Service\AnalyseService;
use App\Service\AvatarProxy;
use Tempest\Http\GenericRequest;
use Tempest\Http\GenericResponse;
use Tempest\Http\Responses\File;
use Tempest\Http\Responses\Invalid;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Ok;
use Tempest\Http\Responses\Redirect;
use Tempest\Http\Status;
use Tempest\Router\Get;
use Tempest\Router\Post;

use function Tempest\view;

final readonly class FrontController
{
    public function __construct(
        private AnalyseService $analyseService,
        private AvatarProxy $avatarProxy,
        private DatabaseRepository $repository,
    ) {}

    #[Get('/')]
    public function index()
    {
        $users = $this->repository->getFollowedUsers();
        $trackedUsers = $this->repository->getTrackedUsers();

        return view('./View/index.view.php', users: $users, trackedUsers: $trackedUsers);
    }

    #[Get('/show')]
    public function show(GenericRequest $request)
    {
        $username = $request->get('username');

        if ($username === null) {
            return new NotFound();
        }

        return view('./View/show.view.php', ...[
            'username' => $username,
            'result' => $this->analyseService->analyse($username),
        ]);
    }

    #[Get('/avatar')]
    public function avatar(GenericRequest $request)
    {
        $avatar = $request->get('avatar');

        if ($avatar === null) {
            return new NotFound();
        }

        $filepath = $this->avatarProxy->get($avatar);

        return new File($filepath);
    }

    #[Post('/track')]
    public function addTrackedUser(GenericRequest $request)
    {
        $username = $request->get('username');

        if ($username === null) {
            return new GenericResponse(Status::BAD_REQUEST);
        }

        $this->repository->saveTrackedUser($username);

        return new Redirect('/');
    }

    #[Post('/untrack')]
    public function removeTrackedUser(GenericRequest $request)
    {
        $username = $request->get('username');

        if ($username === null) {
            return new GenericResponse(Status::BAD_REQUEST);
        }

        $this->repository->removeTrackedUser($username);

        return new Redirect('/');
    }
}
