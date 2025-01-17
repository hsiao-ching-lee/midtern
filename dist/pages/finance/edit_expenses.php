<?php
require __DIR__ . '/../parts/init.php';
$title = "平台支出編輯";
$pageName = "edit";

// 讀取該筆資料
$bn_id = isset($_GET['bn_id']) ? intval($_GET['bn_id']) : 0;
$sql = "SELECT * FROM expenses WHERE id=$bn_id";
$r = $pdo->query($sql)->fetch();
if (empty($r)) {
  // 如果沒有對應的資料，就跳走
  header('Location: expenses.php');
  exit;
}

// 取得所有 manager_account 作為選項
$sql_managers = "SELECT id, manager_account FROM manager";
$managers = $pdo->query($sql_managers)->fetchAll();
?>
<?php include ROOT_PATH . 'dist/pages/parts/head.php' ?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include ROOT_PATH . 'dist/pages/parts/navbar.php' ?>
    <?php include ROOT_PATH . 'dist/pages/parts/sidebar.php' ?>
    <main class="app-main pt-5">
      <div class="app-content-header">

        <div class="container-fluid">
          <div class="row mt-4">
            <div class="col-6">
              <h2>編輯支出資料</h2>
              <form id="editForm" action="edit_expenses-api.php" method="POST" novalidate>
                <div class="mb-3">
                  <label for="expenses_id" class="form-label">支出編號</label>
                  <input type="text" class="form-control" id="expenses_id" name="expenses_id" value="<?= $r['id'] ?>"
                    readonly>
                </div>
                <div class="mb-3">
                  <label for="expense_purpose" class="form-label">支出項目</label>
                  <select class="form-select" id="expense_purpose" name="expense_purpose" required>
                    <option value="線上認養" <?= $r['expense_purpose'] == '線上認養' ? 'selected' : '' ?>>線上認養</option>
                    <option value="食品採購" <?= $r['expense_purpose'] == '食品採購' ? 'selected' : '' ?>>食品採購</option>
                    <option value="醫療費用" <?= $r['expense_purpose'] == '醫療費用' ? 'selected' : '' ?>>醫療費用</option>
                    <option value="行政開支" <?= $r['expense_purpose'] == '行政開支' ? 'selected' : '' ?>>行政開支</option>
                    <option value="活動費用" <?= $r['expense_purpose'] == '活動費用' ? 'selected' : '' ?>>活動費用</option>
                    <option value="器材採購" <?= $r['expense_purpose'] == '器材採購' ? 'selected' : '' ?>>器材採購</option>
                    <option value="設備維護" <?= $r['expense_purpose'] == '設備維護' ? 'selected' : '' ?>>設備維護</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="amount" class="form-label">支出金額</label>
                  <input type="number" class="form-control" id="amount" name="amount" value="<?= $r['amount'] ?>"
                    required>
                  <div id="error-message"></div>
                </div>
                <div class="mb-3">
                  <label for="expense_date" class="form-label">支出日期</label>
                  <input type="date" class="form-control" id="expense_date" name="expense_date"
                    value="<?= $r['expense_date'] ?>" required>
                  <div id="error-message"></div>
                </div>
                <div class="mb-3" id="e_description">
                  <label for="e_description" class="form-label">支出描述</label>
                  <textarea class="form-control" id="e_description" name="e_description"
                    value="<?= $r['e_description'] ?>" required><?= $r['e_description'] ?></textarea>
                  <div id="error-message"></div>
                </div>
                <div class="mb-3" style="display: none;">
                  <label for="refund_id" class="form-label">退款編號</label>
                  <input type="text" class="form-control" id="refund_id" name="refund_id" value="<?= $r['refund_id'] ?>"
                    readonly>
                </div>
                <div class="mb-3">
                  <label for="created_by" class="form-label">記錄人員</label>
                  <select class="form-select" id="created_by" name="created_by" required>
                    <option value="" disabled selected>選擇記錄人員</option>
                    <?php foreach ($managers as $manager): ?>
                      <option value="<?= $manager['id'] ?>" <?= $r['created_by'] == $manager['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($manager['manager_account']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary">更新資料</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
    <?php include ROOT_PATH . 'dist/pages/parts/footer.php' ?>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">編輯結果</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success" role="alert">
            資料編輯成功
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
          <a class="btn btn-primary" href="expenses.php">回到列表頁</a>
        </div>
      </div>
    </div>
  </div>
  <?php include __DIR__ . '/parts/html-scripts.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!--end::Required Plugin(Bootstrap 5)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <script src="<?= ROOT_URL ?>/dist/js/adminlte.js"></script>
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
</body>
<script>
  const myModal = new bootstrap.Modal('#exampleModal');

  const sendData = e => {
    e.preventDefault(); // 阻止表單的默認提交行為
    const form = document.getElementById('editForm');
    let isPass = true; // 用來判斷表單是否通過驗證
    const errorMessage = document.getElementById('error-message'); // 顯示錯誤訊息的元素
    errorMessage.innerHTML = ''; // 清空之前的錯誤訊息
    // 驗證支出金額
    const amount = document.getElementById('amount').value;
    if (!amount || amount <= 0) {
      isPass = false;
      errorMessage.innerHTML += '<p>請輸入有效的支出金額。</p>';
    }

    // 驗證支出日期
    const expenseDate = document.getElementById('expense_date').value;
    if (!expenseDate) {
      isPass = false;
      errorMessage.innerHTML += '<p>請選擇支出日期。</p>';
    }

    // 如果表單驗證通過，提交表單
    if (isPass) {
      const fd = new FormData(form);

      fetch(`edit_expenses-api.php`, {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(obj => {
          if (obj.success) {
            myModal.show(); // 顯示 modal
          } else {
            alert(obj.message || '資料沒有修改');
          }
        })
        .catch(console.warn);
    }
  };

  // 將事件綁定到表單提交事件
  const form = document.getElementById('editForm');
  form.addEventListener('submit', sendData);
</script>

</html>