<?php

// Database connection
$conn = new mysqli('localhost', 'root', 'Akhil25@', 'records');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add record
if (isset($_POST['add'])) {
    if (!empty($_POST['id']) && is_numeric($_POST['id']) && !empty($_POST['name']) && !empty($_POST['email'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $conn->query("INSERT INTO details (id, name, email) VALUES ('$id', '$name', '$email')");
    } else {
        echo "<script>alert('All fields are required and ID must be a number!');</script>";
    }
}

// Delete record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM details WHERE id=$id");
}

// Update record
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("UPDATE details SET name='$name', email='$email' WHERE id=$id");
}

// Fetch records
$result = $conn->query("SELECT * FROM details ORDER BY id DESC");

// For editing
$edit = false;
$edit_id = '';
$edit_name = '';
$edit_email = '';
if (isset($_GET['edit'])) {
    $edit = true;
    $edit_id = $_GET['edit'];
    $row = $conn->query("SELECT * FROM details WHERE id=$edit_id")->fetch_assoc();
    $edit_name = $row['name'];
    $edit_email = $row['email'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>📝 Details Manager</title>
    <style>
        body {
            background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: 40px auto 0 auto;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            padding: 32px 36px 24px 36px;
        }
        h2 {
            text-align: center;
            color: #ff7e5f;
            margin-bottom: 24px;
            font-size: 2em;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 28px;
            background: #f9f9f9;
            padding: 18px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #fda08522;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            border: 1px solid #ffb88c;
            border-radius: 6px;
            font-size: 1em;
            outline: none;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="email"]:focus {
            border: 1.5px solid #ff7e5f;
        }
        button {
            background: linear-gradient(90deg, #ffb88c 0%, #ff7e5f 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 0;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 6px;
        }
        button:hover {
            background: linear-gradient(90deg, #ff7e5f 0%, #ffb88c 100%);
            transform: scale(1.03);
        }
        .cancel-link {
            display: inline-block;
            margin-top: 8px;
            color: #ff7e5f;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s;
        }
        .cancel-link:hover {
            color: #ffb88c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px #fda08522;
        }
        th, td {
            padding: 12px 10px;
            text-align: center;
            overflow: hidden;
            scroll: hidden;
            white-space: nowrap;
            overflow-y: auto;
            
        }
        th {
            background: linear-gradient(90deg, #ffb88c 0%, #ff7e5f 100%);
            color: #fff;
            font-size: 1.1em;
        }
        tr:nth-child(even) {
            background: #fff7f0;
        }
        tr:hover td {
            background: #ffe0c7;
            transition: background 0.2s;
        }
        .action-btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            margin: 0 2px;
            font-weight: bold;
            font-size: 1em;
            transition: background 0.2s, color 0.2s;
        }
        .edit-btn {
            background: #ffe082;
            color: #b26a00;
        }
        .edit-btn:hover {
            background: #ffd54f;
            color: #ff6f00;
        }
        .delete-btn {
            background: #ff8a80;
            color: #b71c1c;
        }
        .delete-btn:hover {
            background: #ff5252;
            color: #fff;
        }
        @media (max-width: 700px) {
            .container { padding: 12px 4px; }
            table, th, td { font-size: 0.95em; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $edit ? "✏️ Edit" : "➕ Add"; ?> Details</h2>
        <form method="post">
            <?php if ($edit): ?>
                <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <?php else: ?>
                <label>
                    🆔 ID:
                    <input type="text" name="id" required>
                </label>
            <?php endif; ?>
            <label>
                👤 Name:
                <input type="text" name="name" value="<?php echo htmlspecialchars($edit_name); ?>" required>
            </label>
            <label>
                📧 Email:
                <input type="email" name="email" value="<?php echo htmlspecialchars($edit_email); ?>" required>
            </label>
            <button type="submit" name="<?php echo $edit ? 'update' : 'add'; ?>">
                <?php echo $edit ? '🔄 Update' : '➕ Add'; ?>
            </button>
            <?php if ($edit): ?>
                <a class="cancel-link" href="p1.php">❌ Cancel</a>
            <?php endif; ?>
        </form>

        <h2>📋 Details List</h2>
        <table>
            <tr>
                <th>🆔 ID</th>
                <th>👤 Name</th>
                <th>📧 Email</th>
                <th>⚙️ Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <a class="action-btn edit-btn" href="p1.php?edit=<?php echo $row['id']; ?>">✏️ Edit</a>
                    <a class="action-btn delete-btn" href="p1.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?');">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>