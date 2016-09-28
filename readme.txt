--------------------------------------------------------------------
- Система управления пользователями на YII2 - basic
--------------------------------------------------------------------


------
Создание шаблона миграции для таблицы USERS

	php yii migrate/create create_user_table

------

Запуск миграции

	php yii migrate/up

------

Прописываем свой класс в файл config\web.php

        'user' => [
            'identityClass' => 'app\models\User',
        ],


------

Включаем RBAC

	В файлах config\web.php и config\console.php добавляем

	        'authManager' => [
	            'class' => 'yii\rbac\DbManager',
	        ],

	и запускаем миграцию

	php yii migrate --migrationPath=@yii/rbac/migrations/

	--------

	подготовка файла ролей и разрешений

	готовим файл commands\RbacController.php
	и запускаем на выполнение

	php yii rbac/init
------

