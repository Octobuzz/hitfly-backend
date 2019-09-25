<?php


namespace App\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Traits\HasRelationshipObservables;
use Encore\Admin\Auth\Database\Role as AdminRole;

class Role extends AdminRole
{
    use HasRelationshipObservables;
    use HasBelongsToManyEvents;
}
