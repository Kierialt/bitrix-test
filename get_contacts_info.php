<?php
require_once 'BitrixAPI.php';

// Ваш вебхук для вызова REST API
$webhookUrl = 'https://b24-und92u.bitrix24.ru/rest/1/1dh2605d0ed6g3q9/';

// Создаем экземпляр API
$bitrix = new BitrixAPI($webhookUrl);

try {
    echo "Получение информации о контактах...\n\n";
    
    // Получаем все контакты с основными полями
    $contacts = $bitrix->getContacts([
        'ID',
        'NAME', 
        'LAST_NAME',
        'PHONE',
        'EMAIL'
    ]);
    
    // Получаем все сделки с привязкой к контактам
    $deals = $bitrix->getDeals([
        'ID',
        'TITLE',
        'CONTACT_ID',
        'OPPORTUNITY',
        'CURRENCY_ID'
    ]);
    
    // Группируем сделки по контактам
    $dealsByContact = [];
    foreach ($deals as $deal) {
        if (!empty($deal['CONTACT_ID'])) {
            $contactId = $deal['CONTACT_ID'];
            if (!isset($dealsByContact[$contactId])) {
                $dealsByContact[$contactId] = [];
            }
            $dealsByContact[$contactId][] = [
                'id' => $deal['ID'],
                'title' => $deal['TITLE'],
                'amount' => $deal['OPPORTUNITY'],
                'currency' => $deal['CURRENCY_ID']
            ];
        }
    }
    
    // Формируем результат
    $result = [];
    
    foreach ($contacts as $contact) {
        $contactId = $contact['ID'];
        $contactDeals = isset($dealsByContact[$contactId]) ? $dealsByContact[$contactId] : [];
        
        // Извлекаем телефон и email из массивов
        $phone = '';
        $email = '';
        
        if (!empty($contact['PHONE']) && is_array($contact['PHONE'])) {
            $phone = $contact['PHONE'][0]['VALUE'] ?? '';
        }
        
        if (!empty($contact['EMAIL']) && is_array($contact['EMAIL'])) {
            $email = $contact['EMAIL'][0]['VALUE'] ?? '';
        }
        
        $result[] = [
            'id' => $contactId,
            'name' => trim(($contact['NAME'] ?? '') . ' ' . ($contact['LAST_NAME'] ?? '')),
            'phone' => $phone,
            'email' => $email,
            'deals_count' => count($contactDeals),
            'deals' => $contactDeals
        ];
    }
    
    // Выводим результат в формате JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    $error = [
        'error' => true,
        'message' => $e->getMessage()
    ];
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($error, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>

