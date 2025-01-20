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

// 處理刪除請求
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // 建立刪除的 SQL 指令
    $delete_sql = "DELETE FROM users WHERE user_id = $delete_id";

    // 執行刪除操作
    if (mysqli_query($link, $delete_sql)) {
        $message = "刪除成功！";
    } else {
        $message = "刪除失敗！";
    }
} else {
    $message = "未指定要刪除的資料！";
}

// 關閉資料庫連接
mysqli_close($link);
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
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
            margin: 0;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }
        .message {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .success {
            color: green;
        }
        .failure {
            color: red;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #45a049;
        }
        .button:focus {
            outline: none;
        }
    </style>
<!--begin::Body-->
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
                            <h3 class="mb-0">刪除會員</h3>
                        </div>
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
                    <a href="logout.php" class="btn btn-danger">登出</a>
                    <div class="container">
        <div class="message <?php echo (isset($message) && strpos($message, "成功") !== false) ? 'success' : 'failure'; ?>">
            <?php echo $message; ?>
        </div>

        <a href="members_list.php" class="button">返回會員列表</a>
    </div>

    <script>
        // 讓頁面顯示一段時間後自動跳轉到會員列表頁
        setTimeout(function() {
            window.location.href = 'members_list.php';
        }, 3000); // 3秒後自動跳轉
    </script>

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

