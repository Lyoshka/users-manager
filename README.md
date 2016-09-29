## Система управления пользователями на YII2 - basic ##



----------
Создание шаблона миграции для таблицы USERS

	`php yii migrate/create create_user_table`

----------

Запуск миграции

	`php yii migrate/up`


----------


Прописываем свой класс в файл config\web.php

        `'user' => [`
    		'identityClass' => 'app\models\User',
    	],


