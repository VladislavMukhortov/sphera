#### 1. .env.example -> .env (заполнить переменные)
#### 2. cd /{project_directory} && docker-compose up -d
#### 3. composer install
#### 4. npm install
#### 5. php artisan migrate:fresh --seed

#### 6. FCM:
> - Создать аккаунт на firebase.google.com. Данные подключения сохранить в .json, в корне -> прописать файл в .env 
(Пример: *FIREBASE_CREDENTIALS=firebase-credentials.json*)
> - Запуск локального FCM: cd firebase/ && firebase emulators:start
> - Получить свой FCM device token -> http://localhost:5000/
> - Проверить FCM можно в том числе через Postman -> FCM Test Notification request

#### 7. Авторизация через сторонние сервисы:
> - Google: Вписать данные с аккаунта(п.4) в .env
> - Facebook: Вписать данные с аккаунта(developers.facebook.com) в .env
