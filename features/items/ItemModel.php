<?php
require_once __DIR__ . '/../../core/Database.php';

class ItemModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllPublicItems() {
        // Fetch Identifiable Items
        $queryIdentifiable = "SELECT i.item_id, i.category, i.date_reported, i.location_found, i.status, i.item_type,
                                     id_i.visible_name, id_i.generalized_description
                              FROM Item i
                              JOIN Identifiable_Item id_i ON i.item_id = id_i.item_id
                              WHERE i.status != 'Returned'
                              ORDER BY i.date_reported DESC";
        $stmtId = $this->db->prepare($queryIdentifiable);
        $stmtId->execute();
        $identifiable = $stmtId->fetchAll();

        // Fetch Unidentifiable Items (blind listing)
        $queryUnidentifiable = "SELECT i.item_id, i.category, i.date_reported, i.location_found, i.status, i.item_type,
                                       un_i.generalized_description
                                FROM Item i
                                JOIN Unidentifiable_Item un_i ON i.item_id = un_i.item_id
                                WHERE i.status != 'Returned'
                                ORDER BY i.date_reported DESC";
        $stmtUnid = $this->db->prepare($queryUnidentifiable);
        $stmtUnid->execute();
        $unidentifiable = $stmtUnid->fetchAll();

        return [
            'identifiable' => $identifiable,
            'unidentifiable' => $unidentifiable
        ];
    }

    public function getItemsByFaculty($faculty_id) {
        $query = "SELECT i.*, 
                         id_i.visible_name, id_i.generalized_description as id_desc,
                         un_i.generalized_description as un_desc, un_i.hidden_description
                  FROM Item i
                  LEFT JOIN Identifiable_Item id_i ON i.item_id = id_i.item_id
                  LEFT JOIN Unidentifiable_Item un_i ON i.item_id = un_i.item_id
                  WHERE i.logged_by_faculty_id = :faculty_id
                  ORDER BY i.date_reported DESC";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':faculty_id', $faculty_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_items,
                    SUM(CASE WHEN status = 'Returned' THEN 1 ELSE 0 END) as returned_items,
                    SUM(CASE WHEN status = 'Claim Pending' THEN 1 ELSE 0 END) as pending_claims
                  FROM Item";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createIdentifiableItem($faculty_id, $category, $location, $visible_name, $description) {
        try {
            $this->db->beginTransaction();

            $date_reported = date('Y-m-d H:i:s');
            $queryItem = "INSERT INTO Item (logged_by_faculty_id, category, date_reported, location_found, status, item_type)
                          VALUES (:faculty_id, :category, :date_reported, :location, 'Listed', 'Identifiable')";
            $stmt = $this->db->prepare($queryItem);
            $stmt->execute([
                ':faculty_id' => $faculty_id,
                ':category' => $category,
                ':date_reported' => $date_reported,
                ':location' => $location
            ]);
            
            $item_id = $this->db->lastInsertId();

            $querySub = "INSERT INTO Identifiable_Item (item_id, visible_name, generalized_description)
                         VALUES (:item_id, :visible_name, :description)";
            $stmtSub = $this->db->prepare($querySub);
            $stmtSub->execute([
                ':item_id' => $item_id,
                ':visible_name' => $visible_name,
                ':description' => $description
            ]);

            $this->db->commit();
            return $item_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function createUnidentifiableItem($faculty_id, $category, $location, $description, $hidden_description) {
        try {
            $this->db->beginTransaction();

            $date_reported = date('Y-m-d H:i:s');
            $queryItem = "INSERT INTO Item (logged_by_faculty_id, category, date_reported, location_found, status, item_type)
                          VALUES (:faculty_id, :category, :date_reported, :location, 'Listed', 'Unidentifiable')";
            $stmt = $this->db->prepare($queryItem);
            $stmt->execute([
                ':faculty_id' => $faculty_id,
                ':category' => $category,
                ':date_reported' => $date_reported,
                ':location' => $location
            ]);
            
            $item_id = $this->db->lastInsertId();

            $querySub = "INSERT INTO Unidentifiable_Item (item_id, generalized_description, hidden_description)
                         VALUES (:item_id, :description, :hidden_description)";
            $stmtSub = $this->db->prepare($querySub);
            $stmtSub->execute([
                ':item_id' => $item_id,
                ':description' => $description,
                ':hidden_description' => $hidden_description
            ]);

            $this->db->commit();
            return $item_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getItemById($item_id) {
        $query = "SELECT i.*, 
                         id_i.visible_name, id_i.generalized_description as id_desc,
                         un_i.generalized_description as un_desc, un_i.hidden_description
                  FROM Item i
                  LEFT JOIN Identifiable_Item id_i ON i.item_id = id_i.item_id
                  LEFT JOIN Unidentifiable_Item un_i ON i.item_id = un_i.item_id
                  WHERE i.item_id = :item_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function updateItemStatus($item_id, $status) {
        $query = "UPDATE Item SET status = :status WHERE item_id = :item_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':status' => $status,
            ':item_id' => $item_id
        ]);
    }
}
