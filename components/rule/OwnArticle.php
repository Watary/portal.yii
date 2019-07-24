<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 2019-07-24
 * Time: 12:09
 */
namespace app\components\rule;

use yii\rbac\Rule;

/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 */
class OwnArticle extends Rule
{
    public $name = 'ownArticle';

    /**
     * @param string|int $user the user ID.
     * @param string $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['article']) ? $params['article']->id_author == $user : false;
    }
}