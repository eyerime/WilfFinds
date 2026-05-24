<?php
require_once __DIR__ . '/../../core/Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function authenticate($employee_id, $password) {
        $query = "SELECT f.person_id, f.employee_id, f.password_hash, f.department, p.first_name, p.last_name 
                  FROM Faculty_User f 
                  JOIN Person p ON f.person_id = p.person_id 
                  WHERE f.employee_id = :employee_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']); // Don't return the hash
            return $user;
        }
        return false;
    }

    public function getUserById($person_id) {
        $query = "SELECT f.person_id, f.employee_id, f.department, p.first_name, p.last_name 
                  FROM Faculty_User f 
                  JOIN Person p ON f.person_id = p.person_id 
                  WHERE f.person_id = :person_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':person_id', $person_id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
