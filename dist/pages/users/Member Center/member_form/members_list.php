<?php
// 設定資料庫連接參數
$host = "127.0.0.1";
$db_username = "root";
$db_password = "P@ssw0rd";
$database = "pet_proj";

// 建立資料庫連接
$link = mysqli_connect($host, $db_username, $db_password, $database);

// 檢查連接是否成功
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 設置編碼，防止中文亂碼
mysqli_query($link, "SET NAMES utf8");

// 處理搜尋條件
$search_query = "";
$search_type = ""; // 搜尋類別

if (isset($_GET['search'])) {
    // 使用 mysqli_real_escape_string() 來防止 SQL 注入
    $search_query = mysqli_real_escape_string($link, $_GET['search']);
}

if (isset($_GET['search_type'])) {
    // 搜尋類別
    $search_type = $_GET['search_type'];
}

// 處理刪除請求
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // 建立刪除的 SQL 指令
    $delete_sql = "DELETE FROM users WHERE user_id = $delete_id";

    // 執行刪除操作
    if (mysqli_query($link, $delete_sql)) {
        echo "<script>alert('刪除成功'); window.location.href='members_list.php';</script>";
    } else {
        echo "<script>alert('刪除失敗'); window.location.href='members_list.php';</script>";
    }
}

// 撰寫 SQL 查詢指令來提取會員資料，並根據搜尋條件進行過濾
$sql = "SELECT user_id, user_email, user_password, user_name, user_number, user_address, user_birthday, user_level, profile_picture, user_status FROM users";

// 根據搜尋類別和搜尋字串動態修改SQL查詢
if (!empty($search_query) && !empty($search_type)) {
    switch ($search_type) {
        case 'email':
            $sql .= " WHERE user_email LIKE '%$search_query%'";
            break;
        case 'name':
            $sql .= " WHERE user_name LIKE '%$search_query%'";
            break;
        case 'phone':
            $sql .= " WHERE user_number LIKE '%$search_query%'";
            break;
        case 'address':
            $sql .= " WHERE user_address LIKE '%$search_query%'";
            break;
    }
}
// 執行 SQL 查詢
$result = mysqli_query($link, $sql);

// 檢查查詢是否成功
if (!$result) {
    die("查詢失敗: " . mysqli_error($link));
}

?>




<?php
// 先載入初始化檔案
require __DIR__ . '/../../../parts/init.php';

// 設定標題和頁面名稱
$title = "會員列表";
$pageName = "demo";

// 啟動 Session
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
ob_start();

// 檢查是否已登入
if (!isset($_SESSION['login_session']) || $_SESSION['login_session'] !== true) {
    header("Location: ../../Member Center/mager_login/login.php");  // 如果未登入，跳轉回登入頁面
    exit;
}
?>


<?php include ROOT_PATH . 'dist/pages/parts/head.php' ?>
<!--begin::Body-->
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .search-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-box input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-box select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-box input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
        }
        td img {
            border-radius: 50%;
        }
        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 5px;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        
    </style>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper 網頁的主要內容在這-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php include ROOT_PATH . 'dist/pages/parts/navbar.php' ?>
        <!--end::Header-->

        <!--begin::Sidebar-->
        <?php include ROOT_PATH . 'dist/pages/parts/sidebar.php' ?>
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main pt-5">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">breadcrumb</li>
                        </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->

            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <h1>歡迎來到管理頁面！</h1>
                    <h1>會員列表</h1>

    <div class="container">
        <!-- 搜尋表單 -->
        <div class="search-box">
            <form method="GET" action="members_list.php">
                <select name="search_type">
                    <option value="">選擇搜尋類型</option>
                    <option value="email" <?php if ($search_type == 'email') echo 'selected'; ?>>Email</option>
                    <option value="name" <?php if ($search_type == 'name') echo 'selected'; ?>>姓名</option>
                    <option value="phone" <?php if ($search_type == 'phone') echo 'selected'; ?>>電話</option>
                    <option value="address" <?php if ($search_type == 'address') echo 'selected'; ?>>地址</option>
                </select>
                <input type="text" name="search" placeholder="輸入搜尋內容" value="<?php echo htmlspecialchars($search_query); ?>">

                <input type="submit" value="搜尋">
            </form>
        </div>
        <!-- 顯示搜尋結果 -->
        <table>
            <thead>
                    <th>會員ID</th>
                    <th>會員Email</th>
                    <th>密碼</th>
                    <th>姓名</th>
                    <th>電話</th>
                    <th>地址</th>
                    <th>生日</th>
                    <th>等級</th>
                    <th>頭像</th>
                    <th>操作</th>
                </>
            </thead>
            <tbody>

                <?php
                // 使用 while 迴圈來提取每一行數據並顯示在表格中
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['user_email'] . "</td>";
                    echo "<td>" . $row['user_password'] . "</td>";
                    echo "<td>" . $row['user_name'] . "</td>";
                    echo "<td>" . $row['user_number'] . "</td>";
                    echo "<td>" . $row['user_address'] . "</td>";
                    echo "<td>" . $row['user_birthday'] . "</td>";
                    echo "<td>" . $row['user_level'] . "</td>";

                    // 顯示頭像，如果有圖片檔案
                    if (!empty($row['profile_picture'])) {
                        echo "<td><img src='" . $row['profile_picture'] . "' alt='Profile Picture' width='50' height='50'></td>";
                    } else {
                        echo "<td>無頭像</td>";
                    }


                    echo "<td class='action-links'>";
                    echo "<a href='members_edit.php?user_id=" . $row['user_id'] . "'>編輯</a> | ";
                    echo "<a href='members_list.php?delete_id=" . $row['user_id'] . "' onclick='return confirm(\"確定要刪除此會員嗎？\");'>刪除</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // 釋放結果集
        mysqli_free_result($result);

        // 關閉資料庫連接
        mysqli_close($link);
        ?>
    </div>
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        <?php include ROOT_PATH . 'dist/pages/parts/footer.php' ?>
        <!--end::Footer-->
    </div>
    </div>
   <!--end::App Wrapper-->
  <!--begin::Script-->
  <script>
    const deleteOne = e => {
      e.preventDefault(); //取消超連結導向
      const tr = e.target.closest('tr');
      const [, td_id, td_name] = tr.querySelectorAll('td'); //陣列的解構賦值
      const id = parseInt(td_id.innerHTML);
      const name = td_name.innerHTML;
      console.log('刪除', id, name);
      if (confirm(`是否要刪除編號為 ${id} 名字為 ${name} 的資料?`)) {
        // 使用javascript做跳轉頁面
        location.href = `pet-del.php?id=${id}`;
      }
    }
  </script>
  <!--begin::Third Party Plugin(OverlayScrollbars) 可自定義的覆蓋滾動條-->
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!--end::Required Plugin(Bootstrap 5)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <script src="<?= ROOT_URL ?>dist/js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)-->
  <!--begin::OverlayScrollbars Configure 設定滾動條-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      // 當鼠標離開滾動區域時，滾動條會自動隱藏；允許用戶通過點擊滾動條來進行滾動
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    // DOMContentLoaded確保在DOM完全加載後執行代碼
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        // 初始化滾動條，並傳遞配置選項，如主題和自動隱藏行為
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <?php
  // 如果有設定標誌就顯示alert
  if (isset($_SESSION['show_alert']) && $_SESSION['show_alert']) {
    echo "<script>
            window.onload = function() {
                alert('已刪除編號: ' + {$_SESSION['deleted_id']} + ' ' + '{$_SESSION['deleted_name']}');
            }
          </script>";
    unset($_SESSION['show_alert']); // 使用後清除標誌
    unset($_SESSION['deleted_id']); // 清除 id
    unset($_SESSION['deleted_name']); // 清除 name
  }
  ?>
  <!--end::OverlayScrollbars Configure-->
  <!-- OPTIONAL SCRIPTS 額外功能&實作-->
  <!-- 排序功能 -->
  <script>
    document.querySelectorAll('th a').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const url = new URL(this.href);
        const sort = url.searchParams.get('sort');
        const order = url.searchParams.get('order');

        // 更新所有圖示為預設狀態
        document.querySelectorAll('th a i').forEach(icon => {
          icon.className = 'fa-solid fa-arrows-up-down';
        });

        // 更新當前列的圖示
        if (order === 'asc') {
          this.querySelector('i').className = 'fa-solid fa-solid fa-arrow-up-short-wide';
        } else {
          this.querySelector('i').className = 'fa-solid fa-arrow-down-wide-short';
        }

        // 添加排序參數到當前URL並跳轉
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sort);
        currentUrl.searchParams.set('order', order);
        window.location.href = currentUrl.toString();
      });
    });
  </script>
  <script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      var searchParams = new URLSearchParams();

      for (var pair of formData.entries()) {
        if (pair[1].trim() !== '') {
          searchParams.append(pair[0], pair[1]);
        }
      }

      var queryString = searchParams.toString();
      window.location.href = window.location.pathname + (queryString ? '?' + queryString : '');
    });
  </script>
  <!--end::Script-->
</body>
<!--end::Body-->

</html>
