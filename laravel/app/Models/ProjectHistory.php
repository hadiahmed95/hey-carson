<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectHistory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'project_histories';

    protected $fillable = [
        'project_id',
        'changed_by_name',
        'changed_by_id',
        'role',
        'action',
    ];

    /**
     * Define action constants
     */
    const ACTION_PROJECT_CREATED_BY_CLIENT = 'project_created_by_client';
    const ACTION_PROJECT_CREATED_BY_EXPERT = 'project_created_by_expert';

    const ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_ADMIN = 'project_moved_to_available_by_admin';

    const ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_EXPERT = 'project_moved_to_available_by_expert';

    const ACTION_PROJECT_MATCHED_BY_ADMIN = 'project_matched';
    const ACTION_PROJECT_REASSIGNED_TO_ANOTHER_EXPERT_BY_ADMIN = 'project_reassigned_to_other_expert';
    const ACTION_PROJECT_MATCHED_BY_EXPERT = 'project_matched';

    const ACTION_PROJECT_CLAIMED_BY_EXPERT = 'project_claimed';
    const ACTION_CLIENT_ADDED_EXPERT = 'client_added_expert_to_project';

    const  ACTION_PROJECT_JOINED_BY_EXPERT = 'project_joined';

    const ACTION_PROJECT_AUTO_RELEASED = 'project_released';

    const ACTION_PROJECT_QUOTE_SENT = 'project_quote_sent';

    const ACTION_PROJECT_ACCEPTED_QUOTE = 'project_accepted_quote';

    const ACTION_PROJECT_DECLINED_QUOTE = 'project_declined_quote';

    const ACTION_PROJECT_SCOPE_ADDED = 'project_scope_added';

    const ACTION_PROJECT_SCOPE_ACCEPTED = 'project_scope_accepted';
    const ACTION_PROJECT_SCOPE_DECLINED = 'project_scope_declined';

    const ACTION_PROJECT_SCOPE_EXPIRED = 'project_scope_expired';

    const ACTION_PROJECT_QUOTE_EXPIRED = 'project_quote_expired';

    const ACTION_PROJECT_EXPERT_COMPLETED = 'project_expert_completed';

    const ACTION_PROJECT_REOPENED = 'project_reopened';

    const ACTION_PROJECT_COMPLETED_BY_CLIENT = 'project_completed_by_client';

    const ACTION_AUTO_PROJECT_COMPLETED = 'project_auto_completed';

    const ACTION_PROJECT_MOVED_BACK_TO_COMPLETED = 'project_moved_back_to_completed';

    /**
     * Get the title of an action based on the provided parameters.
     *
     * This method generates a string representing the action title, which may include details such as the client's name,
     * expert's name, the user who made the changes, and the number of hours.
     *
     * @param string      $action       The action identifier.
     * @param string|null $clientName   The name of the client associated with the action. Defaults to `null` if not provided.
     * @param string|null $expertName   The name of the expert associated with the action. Defaults to `null` if not provided.
     * @param string|null $changesBy    The name of the user who made the changes. Defaults to `null` if not provided.
     * @param int|null    $hours        The number of hours associated with the action.
     *
     * @return string The action title, formatted based on the provided parameters.
     */
    public static function getActionTitle(
        string $action,
        ?string $clientName = null,
        ?string $expertName = null,
        ?string $changesBy = null,
        ?int $hours = null
    ): string {
        switch ($action) {
            case self::ACTION_PROJECT_CREATED_BY_CLIENT:
                return "{$clientName} submitted the project.";
            case self::ACTION_PROJECT_CREATED_BY_EXPERT:
                return "{$expertName} submitted the project.";
            case self::ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_ADMIN:
                return "{$changesBy} moved the project to available.";
            case self::ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_EXPERT:
                return "The $changesBy moved the project to available.";
            case self::ACTION_CLIENT_ADDED_EXPERT:
                return "The $changesBy added {$expertName} to the project";
            case self::ACTION_PROJECT_MATCHED_BY_ADMIN:
                return "{$changesBy} matched the project with the expert, {$expertName}.";
            case self::ACTION_PROJECT_REASSIGNED_TO_ANOTHER_EXPERT_BY_ADMIN:
                return "{$changesBy} reassigned the project to expert, {$expertName}.";
            case self::ACTION_PROJECT_CLAIMED_BY_EXPERT:
                return "{$expertName} claimed the project.";
            case self:: ACTION_PROJECT_JOINED_BY_EXPERT:
                return "{$expertName} joined the project.";
            case self::ACTION_PROJECT_AUTO_RELEASED:
                return "System released the project back to available.";
            case self::ACTION_PROJECT_QUOTE_SENT:
                return "{$expertName} sent a custom quote.";
            case self::ACTION_PROJECT_ACCEPTED_QUOTE:
                return "{$clientName} accepted and paid a quote.";
            case self::ACTION_PROJECT_DECLINED_QUOTE:
                return "{$clientName} declined a quote.";
            case self::ACTION_PROJECT_SCOPE_ADDED:
                return "{$expertName} added {$hours} hours to the project scope.";
            case self::ACTION_PROJECT_SCOPE_ACCEPTED:
                return "{$clientName} accepted the scope of {$hours} hours.";
            case self::ACTION_PROJECT_SCOPE_DECLINED:
                return "{$clientName} declined the scope of {$hours} hours.";
            case self::ACTION_PROJECT_SCOPE_EXPIRED:
                return "The scope of {$hours} hours has been expired.";
            case self::ACTION_PROJECT_QUOTE_EXPIRED:
                return "The quote of {$hours} hours has been expired.";
            case self::ACTION_PROJECT_EXPERT_COMPLETED:
                return "{$expertName} marked the project as 'Completed'.";
            case self::ACTION_PROJECT_REOPENED:
                return "{$clientName} marked the project as 'Not yet Completed'.";
            case self::ACTION_PROJECT_COMPLETED_BY_CLIENT:
                return "{$clientName} marked the project as 'Completed'.";
            case self::ACTION_AUTO_PROJECT_COMPLETED:
                return "System marked the project as completed.";
            case self::ACTION_PROJECT_MOVED_BACK_TO_COMPLETED:
                return "The quote has expired, and the system has reverted the project back to 'Completed'.";
            default:
                return "Status changed";
        }
    }
}
