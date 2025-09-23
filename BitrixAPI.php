<?php
/**
 * Класс для работы с API Битрикс24
 */
class BitrixAPI
{
    private $webhookUrl;
    
    public function __construct($webhookUrl)
    {
        $this->webhookUrl = $webhookUrl;
    }
    
    /**
     * Выполняет запрос к API Битрикс24
     * 
     * @param string $method - метод API
     * @param array $params - параметры запроса
     * @return array - результат запроса
     */
    public function callMethod($method, $params = [])
    {
        $url = $this->webhookUrl . $method;
        
        $data = http_build_query($params);
        
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            ]
        ]);
        
        $result = file_get_contents($url, false, $context);
        
        if ($result === false) {
            throw new Exception('Ошибка при выполнении запроса к API');
        }
        
        $response = json_decode($result, true);
        
        if (isset($response['error'])) {
            throw new Exception('Ошибка API: ' . $response['error_description']);
        }
        
        return $response;
    }
    
    /**
     * Создает контакт в CRM
     * 
     * @param array $contactData - данные контакта
     * @return int - ID созданного контакта
     */
    public function createContact($contactData)
    {
        $response = $this->callMethod('crm.contact.add', [
            'fields' => $contactData
        ]);
        
        return $response['result'];
    }
    
    /**
     * Создает сделку в CRM
     * 
     * @param array $dealData - данные сделки
     * @return int - ID созданной сделки
     */
    public function createDeal($dealData)
    {
        $response = $this->callMethod('crm.deal.add', [
            'fields' => $dealData
        ]);
        
        return $response['result'];
    }
    
    /**
     * Получает список контактов
     * 
     * @param array $select - поля для выборки
     * @param array $filter - фильтр
     * @return array - список контактов
     */
    public function getContacts($select = [], $filter = [])
    {
        $params = [];
        
        if (!empty($select)) {
            $params['select'] = $select;
        }
        
        if (!empty($filter)) {
            $params['filter'] = $filter;
        }
        
        $response = $this->callMethod('crm.contact.list', $params);
        
        return $response['result'];
    }
    
    /**
     * Получает список сделок
     * 
     * @param array $select - поля для выборки
     * @param array $filter - фильтр
     * @return array - список сделок
     */
    public function getDeals($select = [], $filter = [])
    {
        $params = [];
        
        if (!empty($select)) {
            $params['select'] = $select;
        }
        
        if (!empty($filter)) {
            $params['filter'] = $filter;
        }
        
        $response = $this->callMethod('crm.deal.list', $params);
        
        return $response['result'];
    }
}
?>

