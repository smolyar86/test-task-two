## Тестовое задание №2
Для хранения учетных записей пользователей, в проекте была создана следующая таблица в БД MySQL:

```sql
create table users
(
    id int auto_increment,
    name varchar(64) not null,
    email varchar(256) not null,
    constraint users_pk
        primary key (id),
    constraint users_email
        unique (email)
);
 ```

Пользователь может менять адрес своей электронной почты, код сервиса, который выполняет это действие, приведен ниже:
```php
class UserEmailChangerService
{
    private \PDO $db;
 
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
 
    /**
     * @param int $userId
     * @param string $email
     *
     * @return void
     *
     * @throws \PDOException
     */
    public function changeEmail(int $userId, string $email): void
    {
        $statement = $this->db->prepare('UPDATE users SET email = :email WHERE id = :id');
 
        $statement->bindParam(':id', $userId, \PDO::PARAM_INT);
        $statement->bindParam(':email', $email, \PDO::PARAM_STR);
        $statement->execute();
    }
}
```

В рамках проекта по защите учетных записей пользователей, руководством поставлена задача уведомлять пользователя о смене его e-mail адреса. Иными словами, при смене адреса электронной почты со старого на новый, на старый адрес система должна отправлять специальное письмо, уведомляющее пользователя о факте смены почтового адреса. Отправка письма выполняется через уже существующий сервис приложения, имеющий интерфейс вида:
```php
interface UserEmailSenderInterface
{
    /**
     * @param string $oldEmail
     * @param string $newEmail
     *
     * @return void
     *
     * @throws EmailSendException
     */
    public function sendEmailChangedNotification(string $oldEmail, string $newEmail): void;
}
```

Необходимо внести изменения в UserEmailChangerService таким образом, чтобы соблюсти указанные бизнес-требования. Рекомендуется предусмотреть возможность наличия (на данный момент или в будущем) других сервисов/модулей приложения, помимо UserEmailChangerService, также изменяющих записи в таблице users
Архитектурой, тестами, стилем и оформлением кода можно пренебречь. В первую очередь, будет оцениваться корректность решения в условиях работы в высококонкурентной среде.

## Установка
- docker compose up
- docker exec -it test-task-two composer install
- docker exec -it test-task-two php migrate.php

## Тестирование конкурентных запросов
- ab -r -k -c 200 -n 200 "http://localhost/?id=1&email=newmail@mail.com"
<p>email и id можно изменить
<p>Выполняем 200 одновременных запросов, в логе отправки писем mails.log, должна быть только одна запись
