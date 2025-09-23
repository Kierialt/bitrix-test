<?php
require_once 'BitrixAPI.php';

// Ваш вебхук для вызова REST API
$webhookUrl = 'https://b24-und92u.bitrix24.ru/rest/1/1dh2605d0ed6g3q9/';

// Создаем экземпляр API
$bitrix = new BitrixAPI($webhookUrl);

// Читаем ID контактов из файла
$contactIds = [];
if (file_exists('contact_ids.txt')) {
    $contactIds = explode(',', file_get_contents('contact_ids.txt'));
    echo "Загружены ID контактов: " . implode(', ', $contactIds) . "\n\n";
} else {
    echo "Файл contact_ids.txt не найден. Сначала запустите create_contacts.php\n";
    exit;
}

// Массив с тестовыми данными для сделок
$dealTitles = [
    'Продажа программного обеспечения',
    'Консультационные услуги',
    'Разработка веб-сайта',
    'Техническая поддержка',
    'Обучение персонала',
    'Аудит IT-инфраструктуры',
    'Внедрение CRM системы',
    'Создание мобильного приложения',
    'Настройка серверов',
    'Дизайн логотипа',
    'SEO оптимизация',
    'Реклама в интернете',
    'Создание интернет-магазина',
    'Система видеонаблюдения',
    'Облачное хранение данных'
];

echo "Создание сделок в CRM Битрикс24...\n\n";

$createdDeals = [];

try {
    for ($i = 0; $i < 15; $i++) {
        // Выбираем случайный контакт
        $randomContactId = $contactIds[array_rand($contactIds)];
        
        // Выбираем случайное название сделки
        $randomTitle = $dealTitles[array_rand($dealTitles)];
        
        // Генерируем случайную сумму сделки (от 10000 до 500000 рублей)
        $randomAmount = rand(10000, 500000);
        
        // Создаем данные для сделки
        $dealData = [
            'TITLE' => $randomTitle,
            'CONTACT_ID' => $randomContactId,
            'OPPORTUNITY' => $randomAmount,
            'CURRENCY_ID' => 'RUB',
            'STAGE_ID' => 'NEW', // Новая сделка
            'TYPE_ID' => 'GOODS' // Тип: товары
        ];
        
        echo "Создаем сделку " . ($i + 1) . ": {$randomTitle} (контакт ID: {$randomContactId}, сумма: {$randomAmount} руб.)\n";
        
        $dealId = $bitrix->createDeal($dealData);
        $createdDeals[] = $dealId;
        
        echo "✓ Сделка создана с ID: {$dealId}\n\n";
    }
    
    echo "Все сделки успешно созданы!\n";
    echo "ID созданных сделок: " . implode(', ', $createdDeals) . "\n";
    
    // Сохраняем ID сделок в файл
    file_put_contents('deal_ids.txt', implode(',', $createdDeals));
    echo "ID сделок сохранены в файл deal_ids.txt\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}
?>

