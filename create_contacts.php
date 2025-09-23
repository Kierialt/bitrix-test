<?php
require_once 'BitrixAPI.php';

// Ваш вебхук для вызова REST API
$webhookUrl = 'https://b24-und92u.bitrix24.ru/rest/1/1dh2605d0ed6g3q9/';

// Создаем экземпляр API
$bitrix = new BitrixAPI($webhookUrl);

// Массив с тестовыми данными для контактов
$testContacts = [
    [
        'NAME' => 'Иван',
        'LAST_NAME' => 'Петров',
        'PHONE' => [['VALUE' => '+7 (999) 123-45-67', 'VALUE_TYPE' => 'WORK']],
        'EMAIL' => [['VALUE' => 'ivan.petrov@example.com', 'VALUE_TYPE' => 'WORK']]
    ],
    [
        'NAME' => 'Анна',
        'LAST_NAME' => 'Сидорова',
        'PHONE' => [['VALUE' => '+7 (999) 234-56-78', 'VALUE_TYPE' => 'WORK']],
        'EMAIL' => [['VALUE' => 'anna.sidorova@example.com', 'VALUE_TYPE' => 'WORK']]
    ],
    [
        'NAME' => 'Михаил',
        'LAST_NAME' => 'Козлов',
        'PHONE' => [['VALUE' => '+7 (999) 345-67-89', 'VALUE_TYPE' => 'WORK']],
        'EMAIL' => [['VALUE' => 'mikhail.kozlov@example.com', 'VALUE_TYPE' => 'WORK']]
    ],
    [
        'NAME' => 'Елена',
        'LAST_NAME' => 'Морозова',
        'PHONE' => [['VALUE' => '+7 (999) 456-78-90', 'VALUE_TYPE' => 'WORK']],
        'EMAIL' => [['VALUE' => 'elena.morozova@example.com', 'VALUE_TYPE' => 'WORK']]
    ],
    [
        'NAME' => 'Дмитрий',
        'LAST_NAME' => 'Волков',
        'PHONE' => [['VALUE' => '+7 (999) 567-89-01', 'VALUE_TYPE' => 'WORK']],
        'EMAIL' => [['VALUE' => 'dmitry.volkov@example.com', 'VALUE_TYPE' => 'WORK']]
    ]
];

echo "Создание контактов в CRM Битрикс24...\n\n";

$createdContacts = [];

try {
    foreach ($testContacts as $index => $contactData) {
        echo "Создаем контакт " . ($index + 1) . ": {$contactData['NAME']} {$contactData['LAST_NAME']}\n";
        
        $contactId = $bitrix->createContact($contactData);
        $createdContacts[] = $contactId;
        
        echo "✓ Контакт создан с ID: {$contactId}\n\n";
    }
    
    echo "Все контакты успешно созданы!\n";
    echo "ID созданных контактов: " . implode(', ', $createdContacts) . "\n";
    
    // Сохраняем ID контактов в файл для использования в других скриптах
    file_put_contents('contact_ids.txt', implode(',', $createdContacts));
    echo "ID контактов сохранены в файл contact_ids.txt\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
?>

