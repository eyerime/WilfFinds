<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Dashboard - WILDFINDS</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
    <header>
        <h1>WILDFINDS</h1>
        <nav>
            <?php if(isset($_SESSION['faculty_person_id'])): ?>
                <a href="admin_dashboard.php">Admin Dashboard</a>
                <a href="../auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="../auth/login.php">Faculty Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Items Reported</h3>
                <div class="number"><?php echo htmlspecialchars($stats['total_items'] ?? 0); ?></div>
            </div>
            <div class="stat-card">
                <h3>Items Returned</h3>
                <div class="number"><?php echo htmlspecialchars($stats['returned_items'] ?? 0); ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending Claims</h3>
                <div class="number"><?php echo htmlspecialchars($stats['pending_claims'] ?? 0); ?></div>
            </div>
        </div>

        <div class="card">
            <div class="tabs">
                <div class="tab active" onclick="switchTab('identifiable')">Identifiable Items</div>
                <div class="tab" onclick="switchTab('unidentifiable')">Unidentifiable Items</div>
            </div>

            <!-- Identifiable Items -->
            <div id="identifiable" class="tab-content active">
                <h2>Identifiable Items</h2>
                <p>These items have a visible name or clear identification.</p>
                <div class="item-grid">
                    <?php if (empty($items['identifiable'])): ?>
                        <p>No identifiable items found.</p>
                    <?php else: ?>
                        <?php foreach ($items['identifiable'] as $item): ?>
                            <div class="item-card">
                                <h4><?php echo htmlspecialchars($item['visible_name']); ?></h4>
                                <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
                                <p><strong>Found At:</strong> <?php echo htmlspecialchars($item['location_found']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($item['date_reported'])); ?></p>
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($item['generalized_description']); ?></p>
                                <p><span class="badge badge-<?php echo str_replace(' ', '-', $item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></span></p>
                                <?php if ($item['status'] === 'Listed'): ?>
                                    <a href="../claims/claim_form.php?item_id=<?php echo $item['item_id']; ?>" class="btn" style="margin-top: 10px; display: block;">Claim Item</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Unidentifiable Items -->
            <div id="unidentifiable" class="tab-content">
                <h2>Unidentifiable Items</h2>
                <p>These items do not have clear identification. Proof of ownership is mandatory.</p>
                <div class="item-grid">
                    <?php if (empty($items['unidentifiable'])): ?>
                        <p>No unidentifiable items found.</p>
                    <?php else: ?>
                        <?php foreach ($items['unidentifiable'] as $item): ?>
                            <div class="item-card">
                                <h4><?php echo htmlspecialchars($item['category']); ?> Item</h4>
                                <p><strong>Found At:</strong> <?php echo htmlspecialchars($item['location_found']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($item['date_reported'])); ?></p>
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($item['generalized_description']); ?></p>
                                <p><span class="badge badge-<?php echo str_replace(' ', '-', $item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></span></p>
                                <?php if ($item['status'] === 'Listed'): ?>
                                    <a href="../claims/claim_form.php?item_id=<?php echo $item['item_id']; ?>" class="btn btn-secondary" style="margin-top: 10px; display: block;">Submit Claim</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            event.target.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
