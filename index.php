<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>ShareBite - Chia sẻ đồ ăn</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fff3e0; margin: 0; padding: 20px; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(255, 152, 0, 0.2); }
        h1 { color: #e65100; text-align: center; border-bottom: 3px dashed #ffb74d; padding-bottom: 15px; }
        .form-post { background-color: #ffe0b2; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input[type="text"] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ffcc80; border-radius: 5px; box-sizing: border-box; }
        button { background-color: #fb8c00; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; font-size: 16px; }
        button:hover { background-color: #f57c00; }
        .food-item { border-left: 5px solid #ff9800; background-color: #fafafa; padding: 15px; margin-bottom: 15px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
        .status-badge { background-color: #4caf50; color: white; padding: 5px 10px; border-radius: 20px; font-size: 13px; }
        .status-done { background-color: #9e9e9e; }
    </style>
</head>
<body>

<div class="container">
    <h1>🍔 ShareBite</h1>
    
    <?php
    // Kết nối vào database demo_t bạn vừa tạo
   // Thông tin kết nối lấy từ InfinityFree của bạn
   $host = "sql202.infinityfree.com";
   $user = "if0_41925173";
   $pass = "JpHgbdU9dZW";
   $dbname = "if0_41925173_sharebitee"; // Tên này phải khớp với tên DB bạn vừa tạo trên web
   
   $conn = new mysqli($host, $user, $pass, $dbname);
   
   // Kiểm tra kết nối (Dòng này rất quan trọng để biết web có lỗi gì)
   if ($conn->connect_error) {
       die("Kết nối thất bại: " . $conn->connect_error);
   }
    
    // Xử lý khi có người đăng đồ ăn mới
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ten = $_POST["ten_mon"];
        $nguoi = $_POST["nguoi_tang"];
        $sql_insert = "INSERT INTO mon_an (ten_mon, nguoi_tang) VALUES ('$ten', '$nguoi')";
        $conn->query($sql_insert);
    }
    ?>

    <div class="form-post">
        <h3>Đăng đồ ăn bạn muốn tặng:</h3>
        <form method="POST" action="">
            <label>Tên món ăn (Kèm số lượng):</label>
            <input type="text" name="ten_mon" placeholder="VD: 2 ổ bánh mì thịt..." required>
            
            <label>Tên người tặng (Hoặc phòng KTX):</label>
            <input type="text" name="nguoi_tang" placeholder="VD: Thuấn - KTX A..." required>
            
            <button type="submit">Chia sẻ ngay!</button>
        </form>
    </div>

    <h3>Danh sách món ăn đang có:</h3>
    <?php
    // Lấy dữ liệu hiển thị ra ngoài
    $sql_select = "SELECT * FROM mon_an ORDER BY id DESC";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Đổi màu badge nếu đồ ăn đã hết
            $badge_class = ($row["trang_thai"] == 'Còn nhận') ? 'status-badge' : 'status-badge status-done';
            
            echo "<div class='food-item'>";
            echo "<div>";
            echo "<strong>" . $row["ten_mon"] . "</strong><br>";
            echo "<small>Người tặng: " . $row["nguoi_tang"] . "</small>";
            echo "</div>";
            echo "<span class='" . $badge_class . "'>" . $row["trang_thai"] . "</span>";
            echo "</div>";
        }
    } else {
        echo "<p>Chưa có ai chia sẻ đồ ăn lúc này.</p>";
    }
    $conn->close();
    ?>
</div>

</body>
</html>