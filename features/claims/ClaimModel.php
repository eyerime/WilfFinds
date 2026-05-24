<?php
require_once __DIR__ . '/../../core/Database.php';

class ClaimModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function hasActiveClaim($identification_number) {
        $query = "SELECT COUNT(*) FROM Claim c
                  JOIN Claimer cl ON c.claimer_id = cl.person_id
                  WHERE cl.identification_number = :id_num AND c.claim_status = 'Pending Review'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_num', $identification_number);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function createClaim($item_id, $first_name, $last_name, $email, $id_type, $id_num, $proof) {
        try {
            $this->db->beginTransaction();

            // Check if claimer exists
            $qCheck = "SELECT person_id FROM Claimer WHERE identification_number = :id_num";
            $stmtCheck = $this->db->prepare($qCheck);
            $stmtCheck->bindParam(':id_num', $id_num);
            $stmtCheck->execute();
            $claimer_id = $stmtCheck->fetchColumn();

            if (!$claimer_id) {
                // Create Person
                $qPerson = "INSERT INTO Person (first_name, last_name, contact_email) VALUES (:fname, :lname, :email)";
                $stmtPerson = $this->db->prepare($qPerson);
                $stmtPerson->execute([':fname' => $first_name, ':lname' => $last_name, ':email' => $email]);
                $person_id = $this->db->lastInsertId();

                // Create Claimer
                $qClaimer = "INSERT INTO Claimer (person_id, identification_type, identification_number) VALUES (:pid, :id_type, :id_num)";
                $stmtClaimer = $this->db->prepare($qClaimer);
                $stmtClaimer->execute([':pid' => $person_id, ':id_type' => $id_type, ':id_num' => $id_num]);
                $claimer_id = $person_id;
            }

            // Create Claim
            $date_submitted = date('Y-m-d H:i:s');
            $qClaim = "INSERT INTO Claim (item_id, claimer_id, ownership_proof, claim_status, date_submitted)
                       VALUES (:item_id, :claimer_id, :proof, 'Pending Review', :date_sub)";
            $stmtClaim = $this->db->prepare($qClaim);
            $stmtClaim->execute([
                ':item_id' => $item_id,
                ':claimer_id' => $claimer_id,
                ':proof' => $proof,
                ':date_sub' => $date_submitted
            ]);

            // Update item status
            $qItem = "UPDATE Item SET status = 'Claim Pending' WHERE item_id = :item_id";
            $stmtItem = $this->db->prepare($qItem);
            $stmtItem->bindParam(':item_id', $item_id);
            $stmtItem->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getPendingClaimsForFacultyItem($faculty_id, $item_id) {
        $query = "SELECT c.*, p.first_name, p.last_name, p.contact_email, cl.identification_type, cl.identification_number
                  FROM Claim c
                  JOIN Item i ON c.item_id = i.item_id
                  JOIN Claimer cl ON c.claimer_id = cl.person_id
                  JOIN Person p ON cl.person_id = p.person_id
                  WHERE i.logged_by_faculty_id = :faculty_id 
                    AND c.item_id = :item_id
                    AND c.claim_status = 'Pending Review'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':faculty_id' => $faculty_id, ':item_id' => $item_id]);
        return $stmt->fetchAll();
    }

    public function getClaimById($claim_id, $faculty_id) {
        $query = "SELECT c.*, i.logged_by_faculty_id 
                  FROM Claim c
                  JOIN Item i ON c.item_id = i.item_id
                  WHERE c.claim_id = :claim_id AND i.logged_by_faculty_id = :faculty_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':claim_id' => $claim_id, ':faculty_id' => $faculty_id]);
        return $stmt->fetch();
    }

    public function updateClaimStatus($claim_id, $status) {
        $query = "UPDATE Claim SET claim_status = :status WHERE claim_id = :claim_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':status' => $status, ':claim_id' => $claim_id]);
    }

    public function processHandoff($claim_id, $faculty_id) {
        try {
            $this->db->beginTransaction();

            $handoff_date = date('Y-m-d H:i:s');
            $qHandoff = "INSERT INTO Handoff (claim_id, faculty_user_id, handoff_date, physical_id_verified) 
                         VALUES (:claim_id, :faculty_id, :handoff_date, 1)";
            $stmtHandoff = $this->db->prepare($qHandoff);
            $stmtHandoff->execute([
                ':claim_id' => $claim_id,
                ':faculty_id' => $faculty_id,
                ':handoff_date' => $handoff_date
            ]);

            // Set item to Returned
            $qItem = "UPDATE Item i JOIN Claim c ON i.item_id = c.item_id 
                      SET i.status = 'Returned' WHERE c.claim_id = :claim_id";
            $stmtItem = $this->db->prepare($qItem);
            $stmtItem->execute([':claim_id' => $claim_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
