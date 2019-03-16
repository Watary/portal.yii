<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->first_name ?> <?= Yii::$app->user->identity->last_name ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> <?= Yii::$app->user->identity->isOnline() ? 'Online' : 'Offline' ?></a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Home', 'icon' => 'fas fa-home', 'url' => ['/admin']],
                    [
                        'label' => 'RBAC',
                        'icon' => 'fas fa-users',
                        'url' => ['#'],
                        'items' => [
                            ['label' => 'Пользователи', 'icon' => 'fas fa-users', 'url' => ['/rbac/user']],
                            ['label' => 'Назначение', 'icon' => 'fas fa-users', 'url' => ['/rbac/assignment']],
                            ['label' => 'Роли', 'icon' => 'fas fa-users', 'url' => ['/rbac/role']],
                            ['label' => 'Розрешения', 'icon' => 'fas fa-users', 'url' => ['/rbac/permission']],
                            ['label' => 'Маршруты', 'icon' => 'fas fa-users', 'url' => ['/rbac/route']],
                            ['label' => 'Правила', 'icon' => 'fas fa-users', 'url' => ['/rbac/rule']],
                            ['label' => 'Меню', 'icon' => 'fas fa-users', 'url' => ['/rbac/menu']],
                        ],
                    ],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
