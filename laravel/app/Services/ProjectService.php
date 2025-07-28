<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    /**
     * @throws \Exception
     */
    public function archive($projectId): void
    {
        $project = Project::find($projectId);

        if (!$project) {
            throw new \Exception("Project doesn't exists");
        }

        try {
            \DB::beginTransaction();

            $invoices = $project->invoices();
            $reviews = $project->review();

            $reviews->delete();
            $invoices->delete();

            $project->delete();

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();

            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    public function restore($projectId): void
    {
        $project = Project::withTrashed()->find($projectId);

        if (!$project) {
            throw new \Exception("Project doesn't exists");
        }

        try {
            \DB::beginTransaction();

            $invoices = $project->invoices();
            $reviews = $project->review();

            $reviews->restore();
            $invoices->restore();

            $project->restore();

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();

            throw $exception;
        }

    }
}
