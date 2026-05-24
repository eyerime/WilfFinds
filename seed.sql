-- Seed data for `lostfound_db`

-- Insert Persons
INSERT INTO `Person` (`person_id`, `first_name`, `last_name`, `contact_email`) VALUES
(1, 'Admin', 'Faculty', 'admin@cit.edu'),
(2, 'Staff', 'Member', 'staff@cit.edu'),
(3, 'John', 'Doe', 'johndoe@student.cit.edu'),
(4, 'Jane', 'Smith', 'janesmith@student.cit.edu');

-- Insert Faculty Users (person_id 1, 2)
-- Password is 'password123' for both
INSERT INTO `Faculty_User` (`person_id`, `employee_id`, `password_hash`, `department`) VALUES
(1, 'EMP001', '$2y$10$2HhLwH0A.9iQ0b5c1B8n6e.Y5v6x9H7b2K1N3j8L5v2m4P7n9d3C2', 'Computer Science'),
(2, 'EMP002', '$2y$10$2HhLwH0A.9iQ0b5c1B8n6e.Y5v6x9H7b2K1N3j8L5v2m4P7n9d3C2', 'Engineering');

-- Insert Claimers (person_id 3, 4)
INSERT INTO `Claimer` (`person_id`, `identification_type`, `identification_number`) VALUES
(3, 'Student ID', 'STU-2023-001'),
(4, 'Student ID', 'STU-2023-002');

-- Insert Items
INSERT INTO `Item` (`item_id`, `logged_by_faculty_id`, `category`, `date_reported`, `location_found`, `status`, `item_type`) VALUES
(1, 1, 'Stationery', '2023-10-01 10:00:00', 'Library', 'Listed', 'Identifiable'),
(2, 1, 'Accessories', '2023-10-02 11:30:00', 'Cafeteria', 'Listed', 'Identifiable'),
(3, 2, 'Electronics', '2023-10-03 14:15:00', 'Room 302', 'Claim Pending', 'Identifiable'),
(4, 1, 'Electronics', '2023-10-04 09:00:00', 'Gym', 'Listed', 'Unidentifiable'),
(5, 2, 'Keys', '2023-10-05 12:45:00', 'Main Gate', 'Listed', 'Unidentifiable'),
(6, 2, 'Accessories', '2023-10-06 16:20:00', 'Library', 'Listed', 'Unidentifiable');

-- Insert Identifiable Items
INSERT INTO `Identifiable_Item` (`item_id`, `visible_name`, `generalized_description`) VALUES
(1, 'John Doe''s Notebook', 'A blue spiral notebook with the name John Doe on it.'),
(2, 'Jane Smith ID Lace', 'A CIT-U ID lace with an ID card belonging to Jane Smith.'),
(3, 'Maria''s Flash Drive', 'A red 32GB SanDisk flash drive with a small name tag reading "Maria".');

-- Insert Unidentifiable Items
INSERT INTO `Unidentifiable_Item` (`item_id`, `generalized_description`, `hidden_description`) VALUES
(4, 'A black smartphone with a cracked screen.', 'iPhone 11, lock screen is a picture of a cat, has a dent on the top left corner.'),
(5, 'A set of keys with a keychain.', '3 keys on a ring, keychain is a small rubber Batman figure.'),
(6, 'A pair of wireless earbuds in a charging case.', 'White AirPods Pro, case has a small scratch on the back, name "Alex" engraved inside.');

-- Insert a sample Claim (for item 3, by claimer 3)
INSERT INTO `Claim` (`claim_id`, `item_id`, `claimer_id`, `ownership_proof`, `claim_status`, `date_submitted`) VALUES
(1, 3, 3, 'It is my flash drive, it contains my thesis files in a folder named "Final".', 'Pending Review', '2023-10-07 08:30:00');
