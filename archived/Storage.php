<?php
class Storage
{
    private $file_name = 'data.json';
    private $table = [];
    private $count = 0;

    public function __construct() {
        if (file_exists($this->file_name)) {
            $content = file_get_contents($this->file_name);
            $data = json_decode($content, true);
            if (is_array($data)) {
                $this->table = $data['table'];
                $this->count = $data['count'];
            }
        }
    }

    public function &getTable() {
        return $this->table;
    }

    public function getAmount() {
        return $this->count;
    }

    public function getEntry(int $id) {
        if (isset($this->table[$id])) {
            return $this->table[$id];
        }
        return false;
    }

    public function updateEntry($id, array $entry) {
        if (isset($this->table[$id])) {
            $allowed_keys = ['name' => null, 'message' => null];
            $time = $this->table[$id]['time'];

            $this->table[$id] = array_intersect_key($entry, $allowed_keys);
            $this->table[$id]['time'] = $time;

            $data = json_encode(
                [
                    "table" => $this->table,
                    "count" => $this->count
                ]
            , JSON_PRETTY_PRINT);
            file_put_contents($this->file_name, $data);
        }
    }

    /**
     * Adds new entry to data.json storage
     * 
     * @param integer $value
     */
    public function addEntry($entry) {
        $this->table[] = $entry;

        $data = json_encode(
            [
                "table" => $this->table,
                "count" => ++$this->count
            ]
        , JSON_PRETTY_PRINT);
        file_put_contents($this->file_name, $data);

        return array_key_last($this->table);
    }

    public function remove($id) {
        if (array_key_exists($id, $this->table)) {
            unset($this->table[$id]);

            $data = json_encode(
                [
                    "table" => $this->table,
                    "count" => --$this->count
                ]
            , JSON_PRETTY_PRINT);
            file_put_contents($this->file_name, $data);

        }

    }

    public function reset() {
        file_put_contents($this->file_name, '{"table": [], "count": 0}');
        $this->table = [];
        $this->count = 0;
    }

}