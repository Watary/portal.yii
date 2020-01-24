# Підключаємо RBAC Manager

## Підключаємо RBAC Manager for Yii 2 (mdmsoft/yii2-admin)
В консолі прописуємо команду для встановлення адмінен панелі і RBAC Manager:

	composer require mdmsoft/yii2-admin "~2.0"

## Перші налаштування RBAC Manager
Редагуємо конфігураційний файл (config\web.php), додаємо наступні рядки:

	return [
        'components' => [
            'authManager' => [
                'class' => 'yii\rbac\DbManager',
            ]
        ],
        'modules' => [
            'rbac' => [
                'class' => 'mdm\admin\Module',
                'controllerMap' => [
                    'assignment' => [
                        'class' => 'mdm\admin\controllers\AssignmentController',
                        'idField' => 'id',
                        'usernameField' => 'username',
                    ],
                ],
                'layout' => 'left-menu',
                'mainLayout' => '@app/views/layouts/admin/main.php',
            ]
        ],
        'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                'site/*',
                'post/*',
                'rbac/*',
            ]
        ],
    ];

Редагуємо конфігураційний файл (config\console.php), додаємо наступні рядки:

    $config = [
        'components' => [
            'authManager' => [
                'class' => 'yii\rbac\DbManager',
            ],
        ],
    ];

## Виконуємо міграцї
Для роботи RBAC Manager з базою даних потрібно створити таблиці в базі даних. Для цього можна запустити раніше підготовлені міграції, які виконуються за командою в консолі:

    yii migrate --migrationPath=@mdm/admin/migrations
    
    yii migrate --migrationPath=@yii/rbac/migrations

## Створюємо шаблон для RBAC Manager і адмін панелі
В каталозі *views\layouts* створюємо шаблон для RBAC Manager (він же буде використовуватися і в адмін панелі). Створюємо дерикторію *admin*, а в ній файл *main.php*.

## Унаслідуємо моделі для роботи з користувачами

Для більш зрочної і логічної роботи з користувачами унаслідуємо моделі з модуля RBAC Manager в моделях сайту

### Модель *User*

Для зручної роботи з моделю **User**, приводимо її до такого вигляду:

    <?php
        namespace app\models;
        use Yii;
        use yii\db\ActiveRecord;
        use mdm\admin\models\User as UserModel;
        use yii\helpers\Url;
        
        class User extends UserModel
        {
        
            public function getCount(){
                return User::find()->count();
            }
            
            public static function getUserBuId($id){
                return User::find()
                    ->where(['id' => $id])
                    ->one();
            }
            
            public function uploadFiles($folder = 'main')
            {
                $url = 'uploads/' . $folder . '/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
                if($this->imageFile->saveAs($url)){
                    return $url;
                }else{
                    return NULL;
                }
            }
            
            public function getFriends(){
                return $this->hasMany(Friend::className(), ['id_user' => 'id']);
            }
            
            public function getAvatar(){
                if ($this->avatar) {
                    return Url::home(true) . $this->avatar;
                }else{
                    return Url::home(true) . 'uploads/avatar/avatar.png';
                }
            }
            
        }

Функції в моделі:
- getCount() - функція повертає загальну кількість користувачів в додатку;
- getUserBuId($id) - статична функція повертає повну інформацію про користувача х ідентифікатором **$id**;
- uploadFiles($folder = 'main') - функція завантажує файл на сервер в каталог *uploads/**$folder***. Де ***$folder*** параметр який приймає функція. Тут використовується для загрузки аватара.
- getFriends() - функція повертає список друзів.
- getAvatar() - функція повертає адресу на аватар користувача.

### Модель *Signup*

Для реєстрації користувачів на сайті створимо модель *Signup* і унаслідуємо її з модуля RBAC Manager:

    <?php

        namespace app\models;
    
        use Yii;
        use mdm\admin\models\form\Signup as SignupModel;
    
        /**
         * Signup form
         */
        class Signup extends SignupModel{
    
        }

Для роботи моделі створимо екшен *actionSignup* в контроллері *SiteController*, (і підключаємо модель *use app\models\Signup;*):

    public function actionSignup(){
        $model = new Signup();
        
        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
                return $this->goHome();
            }
        }
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

Для відображення форми реєстрації створимо відповідний вид:

    <?php
        use yii\helpers\Html;
        use yii\bootstrap\ActiveForm;
        /* @var $this yii\web\View */
        /* @var $form yii\bootstrap\ActiveForm */
        /* @var $model \mdm\admin\models\form\Signup */
        $this->title = 'Signup';
        $this->params['breadcrumbs'][] = $this->title;
    ?>
    
    <div class="site-signup">
        <h1><?= Html::encode($this->title) ?></h1>
    
        <p>Please fill out the following fields to signup:</p>
        <?= Html::errorSummary($model)?>
        <?php $form = ActiveForm::begin([
            'id' => 'form-signup',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]);
        ?>
    
        <?= $form->field($model, 'username') ?>
    
        <?= $form->field($model, 'email') ?>
    
        <?= $form->field($model, 'password')->passwordInput() ?>
    
        <?= $form->field($model, 'retypePassword')->passwordInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
