<?php

use yii\db\Migration;

/**
 * Class m221110_021922_init_rbac_add_member_subscriber
 */
class m221110_021922_init_rbac_add_member_subscriber extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221110_021922_init_rbac_add_member_subscriber cannot be reverted.\n";

        return false;
    }
    
    // Use up()/down() to run migration code without a transaction.
    public function up() {
        $auth = Yii::$app->authManager;
        //====== ROLE
        $member = $auth->createRole('member');
        $auth->add($member);
        $subscriber = $auth->createRole('subscriber');
        $auth->add($subscriber);

        $editor = $auth->getRole('editor');
        $auth->addChild($editor, $member);
        $auth->addChild($member, $subscriber);
    }

    // Use up()/down() to run migration code without a transaction.
    public function up3() {
        $auth = Yii::$app->authManager;
        $editor = $auth->getRole('editor');

        // add the rule
        $recommendationLimitRule = new \console\rbac\RecommendationLimitRule;
        $auth->add($recommendationLimitRule);
        $recommendationHistoryLimitRule = new \console\rbac\RecommendationHistoryLimitRule;
        $auth->add($recommendationHistoryLimitRule);
        
        // ====== PERMISSION ===========
        $accessAdmin = $auth->createPermission('accessAdmin');
        $accessAdmin->description = 'Access the Admin Panel';
        $auth->add($accessAdmin);
        
        $viewRecommendation = $auth->createPermission('viewRecommendation');
        $viewRecommendation->description = 'Can view recommendation';
        $auth->add($viewRecommendation);
        
        $viewRecommendationLimit = $auth->createPermission('viewRecommendationLimit');
        $viewRecommendationLimit->description = 'Can view recommendation limit time';
        $viewRecommendationLimit->ruleName = $recommendationLimitRule->name;
        $auth->add($viewRecommendationLimit);
        
        $viewRecommendationHistory = $auth->createPermission('viewRecommendationHistory');
        $viewRecommendationHistory->description = 'Can view recommendation history';
        $auth->add($viewRecommendationHistory);
        
        $viewRecommendationHistoryLimit = $auth->createPermission('viewRecommendationHistoryLimit');
        $viewRecommendationHistoryLimit->description = 'Can view recommendation history limit time';
        $viewRecommendationHistoryLimit->ruleName = $recommendationHistoryLimitRule->name;
        $auth->add($viewRecommendationHistoryLimit);
        //====== ROLE
        $member = $auth->createRole('member');
        $auth->add($member);
        $subscriber = $auth->createRole('subscriber');
        $auth->add($subscriber);

        $managerStock = $auth->getPermission('managerStock');

        $auth->addChild($viewRecommendation, $managerStock);
        $auth->addChild($viewRecommendationHistory, $managerStock);

        $auth->addChild($viewRecommendationLimit, $viewRecommendation);
        $auth->addChild($viewRecommendationHistoryLimit, $viewRecommendationHistory);

        $auth->addChild($member, $viewRecommendationLimit);
        $auth->addChild($member, $viewRecommendationHistoryLimit);

        // as well as the permissions of the "editor" role
        $auth->addChild($editor, $accessAdmin);
        $auth->addChild($editor, $member);
        $auth->addChild($member, $subscriber);

    }
    public function up2() {
        $auth = Yii::$app->authManager;
        
        // ====== PERMISSION ===========
        // add "accessAdmin" permission
        $accessAdmin = $auth->createPermission('accessAdmin');
        $accessAdmin->description = 'Access the Admin Panel';
        $auth->add($accessAdmin);

        $editor = $auth->getRole('editor');
        $admin = $auth->getRole('admin');
        $auth->addChild($editor, $accessAdmin);
        $auth->addChild($admin, $accessAdmin);

    }
    public function up1()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        
        // ====== PERMISSION ===========
        // add "managerPost" permission
        $managerPost = $auth->createPermission('managerPost');
        $managerPost->description = 'Post Manager';
        $auth->add($managerPost);

        // ====== PAGE ===========
        // add "managerPage" permission
        $managerPage = $auth->createPermission('managerPage');
        $managerPage->description = 'Page Manager';
        $auth->add($managerPage);
    
        // ====== STOCK ===========
        // add "managerStock" permission
        $managerStock = $auth->createPermission('managerStock');
        $managerStock->description = 'Stock Manager';
        $auth->add($managerStock);

        //====== ROLE
        $editor = $auth->createRole('editor');
        $auth->add($editor);
        
        $auth->addChild($editor, $managerPost);
        $auth->addChild($editor, $managerPage);

        // add "admin" role and give this role the "updatePost" permission
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $managerStock);

        // as well as the permissions of the "editor" role
        $auth->addChild($admin, $editor);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($editor, 2);
        $auth->assign($admin, 1);

    }

    public function down()
    {
        echo "m221110_021922_init_rbac_webck2 cannot be reverted.\n";

        return false;
    }
}
