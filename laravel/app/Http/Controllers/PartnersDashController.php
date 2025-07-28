<?php


namespace App\Http\Controllers;

use App\Http\Requests\SendSignUpEmailRequest;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PartnersDashController
{
    private UserRepository $repository;
    private ProjectRepository $projectRepository;

    public function __construct(UserRepository $repository, ProjectRepository $projectRepository)
    {
        $this->repository = $repository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param SendSignUpEmailRequest $request
     * @param JsonResponse $response
     * @return JsonResponse
     */
    public function fetchPartnerProjects(Request $request, $partnerId): JsonResponse
    {
        if ($request->header('x-partner-dash-key') !== getenv('PARTNER_AUTH_KEY')) {
            return response()
                ->json()
                ->setStatusCode(401);
        }

        $after  = $request->query('after', null);
        $before = $request->query('before', null);

        $partnerUsers = $this->repository->findUsersByPartnerId($partnerId);

        if (!count($partnerUsers)) {
            return response()
                ->json("No user exists for this partner")
                ->setStatusCode(404);
        }

        $totalProjectsCount = $this->projectRepository->partnertotalProjects($partnerUsers);

        $projects = $this->projectRepository->partnerProjects($partnerUsers, $after, $before);

        $remaining = $this->projectRepository->getRemainingProjectsCount($partnerUsers, $after, $before, $projects, $totalProjectsCount);

        $hasMore = $remaining > 0;

        return response()->json([
            'projects' => $projects,
            'total' => $totalProjectsCount,
            'remaining' => $remaining,
            'hasMore' => $hasMore,
        ])->setStatusCode(200);
    }
}
