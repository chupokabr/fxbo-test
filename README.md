#Тестовое задание для FXBO 

Условия описаны в файле REQUIREMENTS.md

##Фронтовая часть реализована на **Nuxt** как отдельный сервис.

Весь фронт лежит в папке `/front`

Особенности реализации:
* Расчет конвертации валют выполняется на стороне клиента.
* Поддерживаются кросс конвертации валют с любой длинной цепи конвертации 
  (Например: RUB/BTC => RUB/EUR -> EUR/USD -> USD/BTC)
* Изменение/удаление записей происходит по позитивному сценарию. После действия интерфейс сразу показывает изменения пользователю, а сам запрос к API идет в фоне. Если что то пошло не так, то состояние восстанавиется. 
* Дизайн сделан на Tailwind CSS и настроен сборщик

Данные получаем c Backend сервиса через API

##Backend реализован на Symfony 5.

* Источники данных реализованы с поддержкой интерфейса для возможности реализации новых источников
* Работа с источниками данных вынесена в сервис `RateImportService`
* Добавлен компоновщик `AllActiveSourceData` для удобной работы с группой источников. Настройка активных источников вынесена в `config/services.yaml`
* Написаны тесты на основной функционал
* API реализовано через `api-platform`

##Docker

Для локальной разработки и проверки подготовлены docker контейнеры и docker-compose

Контейнеры и настройки расположены в папке `/docker`

##Установка зависимостей (composer и yarn)
> docker run --rm -t --volume ${PWD}:/app composer install --ignore-platform-reqs


##Запуск
> docker-compose up -d --force-recreate --remove-orphans


###Инициализация приложения
> docker-compose exec -T app ./bin/console doctrine:database:create --if-not-exists
> 
> docker-compose exec -T app ./bin/console doctrine:migrations:migrate -n --allow-no-migration
> 
> docker-compose exec -T app ./bin/console doctrine:schema:validate --skip-sync


###Импорт курсов с площадок
> docker-compose exec -T app ./bin/console rate:import

###Запуск тестов
> docker-compose exec -T app ./bin/console doctrine:fixtures:load --no-interaction
> 
> docker-compose exec -T app ./vendor/bin/phpunit


###Адреса сервисов

http://localhost:8888/api - API сервер

http://localhost:3000/ - Страница калькулятора

http://localhost:3000/list - Страница списка котировок

