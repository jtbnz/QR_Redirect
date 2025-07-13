<?php

class IncidentStorage {
    private $dataPath;
    
    public function __construct($dataPath) {
        $this->dataPath = $dataPath;
        $this->ensureDataFile();
    }
    
    private function ensureDataFile() {
        $dir = dirname($this->dataPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if (!file_exists($this->dataPath)) {
            $this->saveData(['incidents' => []]);
        }
    }
    
    private function loadData() {
        $content = file_get_contents($this->dataPath);
        return json_decode($content, true) ?: ['incidents' => []];
    }
    
    private function saveData($data) {
        file_put_contents($this->dataPath, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    public function getAllIncidents() {
        $data = $this->loadData();
        return $data['incidents'];
    }
    
    public function getActiveIncidents() {
        $incidents = $this->getAllIncidents();
        return array_filter($incidents, function($incident) {
            return $incident['status'] === 'active';
        });
    }
    
    public function getIncident($id) {
        $incidents = $this->getAllIncidents();
        foreach ($incidents as $incident) {
            if ($incident['id'] === $id) {
                return $incident;
            }
        }
        return null;
    }
    
    public function createIncident($title, $url, $status = 'active') {
        $data = $this->loadData();
        
        $incident = [
            'id' => uniqid('incident_'),
            'title' => $title,
            'url' => $url,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $data['incidents'][] = $incident;
        $this->saveData($data);
        
        return $incident;
    }
    
    public function updateIncident($id, $updates) {
        $data = $this->loadData();
        
        foreach ($data['incidents'] as &$incident) {
            if ($incident['id'] === $id) {
                if (isset($updates['title'])) {
                    $incident['title'] = $updates['title'];
                }
                if (isset($updates['url'])) {
                    $incident['url'] = $updates['url'];
                }
                if (isset($updates['status'])) {
                    $incident['status'] = $updates['status'];
                }
                $incident['updated_at'] = date('Y-m-d H:i:s');
                
                $this->saveData($data);
                return $incident;
            }
        }
        
        return null;
    }
    
    public function deleteIncident($id) {
        $data = $this->loadData();
        
        $data['incidents'] = array_filter($data['incidents'], function($incident) use ($id) {
            return $incident['id'] !== $id;
        });
        
        $data['incidents'] = array_values($data['incidents']); // Re-index array
        $this->saveData($data);
        
        return true;
    }
}