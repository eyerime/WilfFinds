<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Claims - CIT-U Lost & Found</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <header>
        <h1>CIT-U Lost & Found - Admin</h1>
        <nav>
            <a href="../dashboard/admin_dashboard.php">Back to Dashboard</a>
        </nav>
    </header>

    <div class="container">
        <div class="card">
            <h2>Review Claims for Item #<?php echo htmlspecialchars($item_id); ?></h2>
            
            <div style="background: var(--bg-light); padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
                <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($item['item_type']); ?></p>
                <?php if ($item['item_type'] === 'Unidentifiable'): ?>
                    <p><strong>Hidden Description (For Verification):</strong> <br>
                    <em><?php echo htmlspecialchars($item['hidden_description']); ?></em></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (empty($claims)): ?>
                <p>No pending claims for this item.</p>
            <?php else: ?>
                <?php foreach ($claims as $claim): ?>
                    <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <h4>Claimer: <?php echo htmlspecialchars($claim['first_name'] . ' ' . $claim['last_name']); ?></h4>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($claim['contact_email']); ?></p>
                                <p><strong>ID Presented:</strong> <?php echo htmlspecialchars($claim['identification_type']) . ' - ' . htmlspecialchars($claim['identification_number']); ?></p>
                                <p><strong>Submitted:</strong> <?php echo date('M d, Y H:i', strtotime($claim['date_submitted'])); ?></p>
                                <p style="margin-top: 10px;"><strong>Proof of Ownership:</strong><br>
                                <?php echo nl2br(htmlspecialchars($claim['ownership_proof'])); ?></p>
                            </div>
                            <div style="text-align: right;">
                                <form action="manage_claims.php?item_id=<?php echo $item_id; ?>" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="claim_id" value="<?php echo $claim['claim_id']; ?>">
                                    <input type="hidden" name="action" value="Approve">
                                    <button type="submit" class="btn" style="background-color: var(--success); color: white;" onclick="return confirm('Approve this claim and proceed to handoff?');">Approve</button>
                                </form>
                                <form action="manage_claims.php?item_id=<?php echo $item_id; ?>" method="POST" style="display: inline-block; margin-left: 0.5rem;">
                                    <input type="hidden" name="claim_id" value="<?php echo $claim['claim_id']; ?>">
                                    <input type="hidden" name="action" value="Reject">
                                    <button type="submit" class="btn" style="background-color: var(--danger); color: white;" onclick="return confirm('Reject this claim?');">Reject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
