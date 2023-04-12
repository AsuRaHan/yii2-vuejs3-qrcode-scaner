<?php

use yii\db\Migration;
use app\models\User;
/**
 * Class m221203_153540_seeds_user
 */
class m221203_153540_seeds_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $user = new User();
        $user->username = 'root';
        $user->email = 'root@toor.no';
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash('root');
        $user->auth_key = '';
        $user->created_at = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
        $user->updated_at = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
        $user->save();
        $id = $user->getId();

        $auth = Yii::$app->authManager;

        $permission = $auth->createPermission('sysadmin');
        $permission->description = 'user is main admin';
        $auth->add($permission);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $permission);

        $auth->assign($admin, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "delete all user.\n";
        $this->truncateTable('{{%user}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    }
    public function down()
    {
        echo "m221203_153540_seeds_user cannot be reverted.\n";
        return false;
    }
    */
}