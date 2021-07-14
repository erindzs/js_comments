<?php

header('Content-type: application/json');
$output = [
    'status' => 'failed'
];

include "Storage.php";
$storage = new Storage();


if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get':
            $output = [
                'status' => 'success',
                'message' => 'values received',
                'data' => $storage->getTable()
            ];
            break;
        case 'add':
            if (
                array_key_exists('message', $_POST) &&
                array_key_exists('name', $_POST)
            ) {
                $id = $storage->addEntry([
                    'name' => $_POST['name'],
                    'message' => $_POST['message'],
                    'time' => time()
                ]);
                $output = [
                    'status' => 'success',
                    'message' => 'new entry added',
                    'entry' => [
                        'id' => $id,
                        'name' => $_POST['name'],
                        'message' => $_POST['message'],
                        'time' => time()
                    ]
                ];
            }
            else {
                $output['message'] = '"message" or "name" are not recieved';
            }
            break;
        case 'remove':
            if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
                $storage->remove($_GET['remove']);
                $output = [
                    'status' => 'success', 
                    'message' => 'element ID:' . $_GET['remove'] . ' deleted'
                ];
            }
            else {
                $output['message'] = 'Delete failed. Parameter remove is not numeric.';
            }
            break;
        case 'update':
            if (isset($_GET['id']) && is_numeric($_GET['id']) &&
                isset($_GET['name']) && is_string($_GET['name']) &&
                isset($_GET['message']) && is_string($_GET['message']) 
            ) {
                $entry = [
                    'name' => $_GET['name'],
                    'message' => $_GET['message']
                ];
                $storage->updateEntry($_GET['id'], $entry);

                $output = [
                    'status' => 'success', 
                    'message' => 'element ID:' . $_GET['id'] . ' updated'
                ];
            }

            break;
        default:
            $output['message'] = 'unexpected action';
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);