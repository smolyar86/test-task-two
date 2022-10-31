<?php

declare(strict_types=1);

use App\{Connection, UserEmailChangerService, UserEmailSender, UserNotFoundException};

require __DIR__ . '/../boot.php';

$userId = (int)($_REQUEST['id'] ?? 0);
$newEmail = (string)($_REQUEST['email'] ?? '');

if (!$userId) {
    die('Required parameter :id is empty');
}

if (empty($newEmail)) {
    die('Required parameters :email is empty');
}

try {
    $db = Connection::getInstance()->getPdo();
    $service = new UserEmailChangerService(
        Connection::getInstance()->getPdo(), new UserEmailSender()
    );
    $service->changeEmail($userId, $newEmail);
    echo 'Email has been changed';
} catch (PDOException $e) {
    die('error:' . $e->getMessage());
} catch (UserNotFoundException) {
    die(sprintf('User #%s not found', $userId));
} catch (Throwable $e) {
    die('error:' . $e->getMessage());
}
