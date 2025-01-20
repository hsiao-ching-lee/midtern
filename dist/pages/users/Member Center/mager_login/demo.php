<?php
// 先載入初始化檔案
require_once __DIR__ . '/../../../parts/init.php';

// 設定標題和頁面名稱
$title = "通訊錄列表";
$pageName = "demo";

// 啟動 Session init.php已啟動
// session_start();
// ob_start();

// 檢查是否已登入
if (!isset($_SESSION['login_session']) || $_SESSION['login_session'] !== true) {
    header("Location: login.php");  // 如果未登入，跳轉回登入頁面
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<?php include ROOT_PATH . 'dist/pages/parts/head.php' ?>
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
                            <h3 class="mb-0">這裡是標題</h3>
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
                    <p>這裡是內容</p>
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
    </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= ROOT_URL ?>/dist/js/adminlte.js"></script>
  <!--begin::Third Party Plugin(OverlayScrollbars) 可自定義的覆蓋滾動條-->
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Required Plugin(popperjs for Bootstrap 5) Bootstrap彈出元素"（如工具提示和彈出窗口）-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)-->
  <!--begin::Required Plugin(Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)-->
  <!--begin::Required Plugin(AdminLTE)-->

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
    <!--end::Script-->
</body>
<!--end::Body-->

</html>
