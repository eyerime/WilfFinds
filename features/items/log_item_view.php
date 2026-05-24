<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Found Item - WILDFINDS</title>
    <link rel="stylesheet" href="../../assets/style.css?v=3">
    <style>
        .type-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .type-selector label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: normal;
        }
        #identifiable_fields, #unidentifiable_fields {
            display: none;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: #fafafa;
        }
    </style>
</head>
<body>
    <header>
        <div class="brand-container">
            <img src="../../assets/logo.png" alt="CIT-U Logo" class="brand-logo">
            <h1>WILDFINDS</h1>
        </div>
        <nav>
            <a href="../dashboard/admin_dashboard.php">Admin Dashboard</a>
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h2>Log a New Found Item</h2>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="log_item.php" method="POST">

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Keys">Keys</option>
                        <option value="Stationery">Stationery</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Documents">Documents</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">Location Found</label>
                    <input type="text" id="location" name="location" required>
                </div>

                <div class="form-group">
                    <label>Item Type</label>
                    <div class="type-selector">
                        <label>
                            <input type="radio" name="item_type" value="Identifiable" onchange="toggleFields()" required>
                            Identifiable (e.g., ID Lace, Notebook with name)
                        </label>
                        <label>
                            <input type="radio" name="item_type" value="Unidentifiable" onchange="toggleFields()">
                            Unidentifiable (e.g., Keys, Locked Phone)
                        </label>
                    </div>
                </div>

                <!-- Identifiable Fields -->
                <div id="identifiable_fields">
                    <div class="form-group">
                        <label for="visible_name">Visible Name on Item</label>
                        <input type="text" id="visible_name" name="visible_name">
                    </div>
                    <div class="form-group">
                        <label for="id_description">Generalized Description</label>
                        <textarea id="id_description" name="id_description" rows="3"></textarea>
                    </div>
                </div>

                <!-- Unidentifiable Fields -->
                <div id="unidentifiable_fields">
                    <div class="form-group">
                        <label for="un_description">Public "Blind" Description</label>
                        <textarea id="un_description" name="un_description" rows="3" placeholder="e.g., A black smartphone"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="hidden_description">Hidden Granular Description (NOT Public)</label>
                        <textarea id="hidden_description" name="hidden_description" rows="3" placeholder="e.g., Cracked screen top left, cat wallpaper"></textarea>
                        <small style="color: #666;">Used to verify ownership claims.</small>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <button type="submit" class="btn" style="width: 100%;">Log Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFields() {
            const isIdentifiable = document.querySelector('input[name="item_type"][value="Identifiable"]').checked;

            const idFields = document.getElementById('identifiable_fields');
            const unFields = document.getElementById('unidentifiable_fields');

            if (isIdentifiable) {
                idFields.style.display = 'block';
                unFields.style.display = 'none';

                // set required
                document.getElementById('visible_name').required = true;
                document.getElementById('id_description').required = true;
                document.getElementById('un_description').required = false;
                document.getElementById('hidden_description').required = false;
            } else {
                idFields.style.display = 'none';
                unFields.style.display = 'block';

                // set required
                document.getElementById('visible_name').required = false;
                document.getElementById('id_description').required = false;
                document.getElementById('un_description').required = true;
                document.getElementById('hidden_description').required = true;
            }
        }
    </script>
</body>
</html>
