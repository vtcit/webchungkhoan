<?php

namespace console\rbac;

use yii\rbac\Rule;
// use common\models\Post;

/**
 * Checks if memberID matches user passed via params
 */
class RecommendationHistoryLimitRule extends Rule
{
    public $name = 'isRecommendationHistoryLimit';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        // return isset($params['plans']) ? $params['plans']->createdBy == $user : false;
        return false;
    }
}