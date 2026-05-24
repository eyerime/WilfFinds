<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Claim - CIT-U Lost & Found</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <header>
        <h1>CIT-U Lost & Found</h1>
        <nav>
            <a href="../dashboard/public_dashboard.php">Public Dashboard</a>
        </nav>
    </header>

    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h2>Claim Item</h2>

            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
                <a href="../dashboard/public_dashboard.php" class="btn">Return to Dashboard</a>
            <?php else: ?>
                <div style="background: var(--bg-light); padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
                    <h3>Item Details</h3>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($item['item_type']); ?></p>
                    <?php if ($item['item_type'] === 'Identifiable'): ?>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($item['visible_name']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($item['id_desc']); ?></p>
                    <?php else: ?>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($item['un_desc']); ?></p>
                        <div class="alert alert-warning" style="background: #fff3cd; color: #856404; margin-top: 10px;">
                            <strong>Note:</strong> Since this item is unidentifiable, you MUST provide precise, matching granular details about the item to prove your ownership.
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <a href="../dashboard/public_dashboard.php" class="btn">Return to Dashboard</a>
                <?php else: ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form action="claim_form.php" method="POST">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">

                        <h3>Your Details</h3>
                        <div style="display: flex; gap: 1rem;">
                            <div class="form-group" style="flex: 1;">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Contact Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <div class="form-group" style="flex: 1;">
                                <label for="id_type">Identification Type</label>
                                <select id="id_type" name="id_type" required>
                                    <option value="">-- Select ID --</option>
                                    <option value="Student ID">Student ID</option>
                                    <option value="Employee ID">Employee ID</option>
                                    <option value="Driver License">Driver License</option>
                                    <option value="National ID">National ID</option>
                                    <option value="Other">Other Valid ID</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label for="id_number">ID Number</label>
                                <input type="text" id="id_number" name="id_number" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ownership_proof">Proof of Ownership <?php echo $item['item_type'] === 'Unidentifiable' ? '<span style="color:red;">*</span>' : '(Optional)'; ?></label>
                            <textarea id="ownership_proof" name="ownership_proof" rows="4"
                                      placeholder="Describe unique characteristics (e.g., wallpaper, specific scratches, hidden contents)..."
                                      <?php echo $item['item_type'] === 'Unidentifiable' ? 'required' : ''; ?>></textarea>
                            <small style="color: #666;">Be as detailed as possible to help us verify your claim.</small>
                        </div>

                        <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Submit Claim</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
