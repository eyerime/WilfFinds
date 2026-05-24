<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Handoff - CIT-U Lost & Found</title>
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
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h2>Process Handoff</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <a href="../dashboard/admin_dashboard.php" class="btn" style="width: 100%; text-align: center;">Return to Dashboard</a>
            <?php else: ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="alert alert-warning" style="background: #fff3cd; color: #856404;">
                    <strong>Action Required:</strong> Please physically verify the claimant's ID before releasing the item.
                </div>

                <form action="handoff.php" method="POST" style="margin-top: 1.5rem;">
                    <input type="hidden" name="claim_id" value="<?php echo htmlspecialchars($claim_id); ?>">

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="physical_id_verified" required style="width: auto; margin-right: 10px;">
                            I confirm that I have physically verified the claimant's Identification Type and Number.
                        </label>
                    </div>

                    <button type="submit" class="btn" style="width: 100%; background-color: var(--success); color: white;">Complete Handoff</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
