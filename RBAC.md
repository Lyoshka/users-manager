## **RBAC**

---

### Включаем

В файлах `config\web.php` и `config\console.php` добавляем

`'authManager' => [`

`     'class' => 'yii\rbac\DbManager',`

`],`

и запускаем миграцию

`php yii migrate --migrationPath=@yii/rbac/migrations/`

---

### Подготовка файла ролей и разрешений

готовим файл `commands\RbacController.php`

и запускаем на выполнение

`php yii rbac/init`

---

### Привязка ролей к пользователям

готовим файл `commands\RolesController.php`

и запускаем на выполнение

`php yii roles/assign`

---

### Настройка правил \(Rule\)

готовим файл `commands\AuthorRule.php`

далее надо в файле `commands\RbacController.php`

добавить привязку правил \(для примера\):

`// Создадим еще новое разрешение «Редактирование собственной новости» и ассоциируем его с правилом AuthorRule`

`$updateOwnNews = $auth->createPermission('updateOwnNews');`

`$updateOwnNews->description = 'Редактирование собственной новости';`

`// Указываем правило AuthorRule для разрешения updateOwnNews.`

`$updateOwnNews->ruleName = $authorRule->name;`

`// Запишем все разрешения в БД`

`$auth->add($updateOwnNews);`

`// Теперь добавим наследования. Для роли editor мы добавим разрешение updateOwnNews (редактировать собственную новость),`

`// Роли «Редактор новостей» присваиваем разрешение «Редактирование собственной новости»`

`$auth->addChild($editor,$updateOwnNews);`

---

