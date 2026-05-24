<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WILDFINDS</title>
    <link rel="stylesheet" href="../../assets/style.css?v=3">
</head>
<body>
    <header>
        <div class="brand-container">
            <img src="../../assets/logo.png" alt="CIT-U Logo" class="brand-logo">
            <h1>WILDFINDS</h1>
        </div>
        <nav>
            <a href="../items/log_item.php">Log Found Item</a>
            <a href="public_dashboard.php">Public View</a>
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Items System-wide</h3>
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
            <h2>Items Managed by You</h2>

            <div class="filter-group">
                <label for="statusFilter">Filter by Status:</label>
                <select id="statusFilter" onchange="filterItems()" style="width: auto;">
                    <option value="All">All</option>
                    <option value="Listed">Listed</option>
                    <option value="Claim Pending">Claim Pending</option>
                    <option value="Returned">Returned</option>
                </select>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Date Reported</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <?php if (empty($myItems)): ?>
                        <tr><td colspan="7">You have not logged any items yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($myItems as $item): ?>
                            <tr class="item-row" data-status="<?php echo htmlspecialchars($item['status']); ?>">
                                <td><?php echo $item['item_id']; ?></td>
                                <td><?php echo $item['item_type']; ?></td>
                                <td><?php echo htmlspecialchars($item['category']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($item['date_reported'])); ?></td>
                                <td>
                                    <?php
                                        if ($item['item_type'] == 'Identifiable') {
                                            echo "<strong>" . htmlspecialchars($item['visible_name']) . "</strong><br>";
                                            echo htmlspecialchars($item['id_desc']);
                                        } else {
                                            echo "<em>Public:</em> " . htmlspecialchars($item['un_desc']) . "<br>";
                                            echo "<em>Hidden:</em> " . htmlspecialchars($item['hidden_description']);
                                        }
                                    ?>
                                </td>
                                <td><span class="badge badge-<?php echo str_replace(' ', '-', $item['status']); ?>"><?php echo htmlspecialchars($item['status']); ?></span></td>
                                <td>
                                    <?php if ($item['status'] === 'Claim Pending'): ?>
                                        <a href="../claims/manage_claims.php?item_id=<?php echo $item['item_id']; ?>" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Review Claim</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterItems() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.item-row');

            rows.forEach(row => {
                if (filter === 'All' || row.getAttribute('data-status') === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
