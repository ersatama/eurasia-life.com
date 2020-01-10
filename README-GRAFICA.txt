I. Поднимаем сайт (backend)
================================
01. Установка зависимостей
# composer install

02. Проверка требований
# php requirements.php
- Можно опустить предупреждения отсутствия расширений ICU, ICU Data, PDO PostgreSQL, APC, ImageMagick PHP, Memo
- Для dev-окружения можно опустить предупреждение включенного "Expose PHP" (показывать версию PHP)

03. Иниц. проекта
- Указываем Development или Production
# php init

04. Создаем БД и правим конфиги для доступа к БД "./common/config/main-local.php"

05. Поднимаем миграции
# php yii migrate

06. Создаем хосты "*website*.test" с корневой папкой "frontend/web" и "admin.*website*.test" с корневой папкой "backend/web"

07. Доступ в админку. Для входа в админку (admin.*website*.test) необходимо создать пользователя через команду:
# yii user/create

I. Поднимаем сайт (frontend)
01. На дев(!!!) машине поднимаем frontend-зависимости
# npm install

02. Собираем фронт
Собрать фронтенд и следить за изменениями в файлах фронтенда
# npm run watch

03. Собрать и отправляем prod-версию на боевой:
# npm run deploy-prod


II. http vs https
01. При prod-mode в "./backend/web/.htaccess" и "./frontend/web/.htaccess" поменять на нужные настройки
02. При prod-mode в "./backend/config/main-local.php" и "./frontend/config/main-local.php" поменять настройки куков


III. Форк проекта
================================
01. Поправить все в папке environments
02. Снести миграции
03. Обновить composer.json и package.json
04. Поднять проект


IV. Поднимаем тесты. Только для DEV-машины!
===============================
01. Создаем БД для тестов и правим конфиги для доступа к БД "./common/config/test-local.php"

02. Поднимаем миграции для тестов
# php yii_test migrate

03. Собираем тесты. (Напоминание: запускаем сборку каждый раз после редактирования конфигов codecept)
# codecept build

04. запускаем тесты
# codecept run

Удачи =))



