<?php
require_once 'config.php';

try {
    $sql = "SELECT * FROM volunteers ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats_sql = "SELECT 
        COUNT(*) as total_volunteers,
        COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_volunteers,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_volunteers
    FROM volunteers";
    
    $stats_stmt = $pdo->query($stats_sql);
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>EcoWarriors - Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 20px 0; }
        .stat-card { background: #2E8B57; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #2E8B57; color: white; }
        .status-pending { color: #ffa500; font-weight: bold; }
        .status-approved { color: #2E8B57; font-weight: bold; }
        .btn { background: #2E8B57; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌍 EcoWarriors - Admin Panel</h1>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_volunteers']; ?></div>
                <div>Total Volunteers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['approved_volunteers']; ?></div>
                <div>Approved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending_volunteers']; ?></div>
                <div>Pending</div>
            </div>
        </div>

        <?php if (empty($volunteers)): ?>
            <p>No volunteer submissions yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Interest</th>
                        <th>Availability</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($volunteers as $volunteer): ?>
                    <tr>
                        <td><?php echo $volunteer['id']; ?></td>
                        <td><?php echo htmlspecialchars($volunteer['name']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['email']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['location']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['interest']); ?></td>
                        <td><?php echo htmlspecialchars($volunteer['availability']); ?></td>
                        <td class="status-<?php echo $volunteer['status']; ?>">
                            <?php echo ucfirst($volunteer['status']); ?>
                        </td>
                        <td><?php echo $volunteer['created_at']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <a href="index.html" class="btn">← Back to Main Site</a>
    </div>
</body>
</html>