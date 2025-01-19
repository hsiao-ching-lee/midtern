<?php
/************** 修改api頁面 *****************************************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

#管理者才可看到這頁面
// require __DIR__ . '/parts/admin-required.php';

require __DIR__ . '/../parts/init.php';
header('content-Type: application/json');

$output = [
  'success' => false, # 有沒有新增成功
  'bodyData' => $_POST, # 除錯的用途
  'code' => 0, # 自訂的編號, 除錯的用途
  'error' => '', # 回應給前端的錯誤訊息
];

/************** 查詢商品id(修改規格頁) api***************************/
if (isset($_GET['product_id_check'])) {
  $product_id = intval($_GET['product_id_check']); // 確保是整數
  $sql = "SELECT product_name FROM products WHERE product_id = :product_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['product_id' => $product_id]);
  $row = $stmt->fetch();

  if ($row) {
    echo json_encode(['product_name' => $row['product_name']]);
  } else {
    echo json_encode(['product_name' => '查無此商品']);
  }
  exit;
}
/************** 查詢商品id END*****************/




/******************************修改商品 api***************************** */
if (!empty($_POST['variant_name']) || !empty($_POST['category']) || !empty($_POST['category_name']) || !empty($_POST['order_id']) || !empty($_POST['promotion_name']) || !empty($_POST['promotion_id'])) {

  /************** 修改商品規格 ***********************/
  if (!empty($_POST['variant_name'])) {
    $sql = "UPDATE `product_variants` SET 
  `product_id`=?,
  `variant_name`=?,
  `price`=?,
  `stock_quantity`=?,
  `image_url`=?
  WHERE `variant_id`=? ";

  $stmt = $pdo->prepare($sql);
  $upload_path=null;
  // 測試上傳圖片
  $dir= __DIR__.'/photos/'; #存放圖片的資料夾

  // 篩選檔案類型，決定副檔名
  $exts =[
    'image/jpeg' => '.jpg',
    'image/png'=> '.png',
    'image/webp'=> '.webp'
  ];
    # ********* TODO: 欄位檢查 *************
    // 檢查有無此產品
    // 1.查詢資料庫是否存在該 product_id
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM products WHERE product_id = ?");
    // 2.執行準備好的 SQL 查詢，將變數綁定到 SQL 查詢中的 ? 參數。查詢的結果會保存在 $stmt_check 中。
    $stmt_check->execute([$_POST['product_id']]);
    // 3.獲取結果，使用 fetchColumn 方法取得查詢結果的第一列第一個值。因為 SQL 查詢是 SELECT COUNT(*)，所以返回的值是一個整數，表示符合條件的記錄數。若 product_id 存在於資料表中，$count 會是 1（或更多）；若不存在，$count 會是 0
    $count = $stmt_check->fetchColumn();

    if ($count == 0) {
      // 如果查無此產品，返回錯誤訊息
      $output = [
        'code' => 401, // 自行決定的除錯編號
        'error' => '查無此產品!'
      ];
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif ($_POST['price'] < 0) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '請填寫此規格價位!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif ($_POST['stock'] < 0) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '請填寫此規格的產品庫存!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    }
    
    // 檢查圖片是否有變動
    if(!empty($_POST['originImg'])){
      $upload_path=$_POST['originImg'];
    }
    // 檢查要更新的圖片是否有上傳檔案
    elseif(!empty($_FILES['img'])
      and
      !empty($_FILES['img'])
      and
      !empty($_FILES['img']['error'] == 0)
    ){
      // 檢查副檔名(MIME Type檔案類型)
      if(!empty($exts[$_FILES['img']['type']])){
        // 取得副檔名
        $exts = $exts[$_FILES['img']['type']];
        // 建立隨機檔案名稱
        $file_name = md5($_FILES['img']['name'].uniqid());
        $upload_path = 'photos/'.$file_name.$exts;
        // 將檔案移動到指定資料夾
        if(move_uploaded_file(
          // 暫存檔案的路徑
          $_FILES['img']['tmp_name'],
          $dir.$file_name.$exts
          )) {
            // $output['success']=true; 
            // $output['file']=$file_name.$exts
            // ;
          }

      }
    }else{
      $upload_path=null;
    }

    # ********* TODO END *************
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $_POST['product_id'],
      $_POST['variant_name'],
      $_POST['price'],
      $_POST['stock'],
      $upload_path  ?? null,
      $_POST['variant_id']
    ]);



  }

  /************** 修改商品 ********************************/ elseif (!empty($_POST['category'])) {
    $sql = "UPDATE `products` SET 
  `product_name`=?,
  `product_description`=?,
  `price`=?,
  `category_id`=?,
  `product_status`=?,
  `stock_quantity`=?,
  `image_url`=?
  WHERE `product_id`=? ";

  $upload_path=null;
  // 測試上傳圖片
  $dir= __DIR__.'/photos/'; #存放圖片的資料夾

  // 篩選檔案類型，決定副檔名
  $exts =[
    'image/jpeg' => '.jpg',
    'image/png'=> '.png',
    'image/webp'=> '.webp'
  ];

    # ********* TODO: 欄位檢查 *************

    if (empty($_POST['product_name'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫產品規格!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['category'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有選取商品類別!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['product_status'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有選取商品狀態!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['price'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫此規格價位!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif ($_POST['price'] < 0) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '價位格式錯誤!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['stock'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫庫存!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif ($_POST['stock'] < 0) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '庫存格式錯誤!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    }

    # *** 處理日期
    // if (empty($_POST['birthday'])) {
    //   $birthday = null;
    // } else {
    //   $birthday = strtotime($_POST['birthday']); # 轉換成 timestamp
    //   if ($birthday === false) {
    //     // 如果格式是錯的, 無法轉換
    //     $birthday = null;
    //   } else {
    //     $birthday = date("Y-m-d", $birthday);
    //   }
    // }

    
    // -------------------照片


    // 檢查圖片是否有變動
    if(!empty($_POST['originImg'])){
      $upload_path=$_POST['originImg'];
    }
    // 檢查要更新的圖片是否有上傳檔案
    elseif(!empty($_FILES['img'])
      and
      !empty($_FILES['img'])
      and
      !empty($_FILES['img']['error'] == 0)
    ){
      // 檢查副檔名(MIME Type檔案類型)
      if(!empty($exts[$_FILES['img']['type']])){
        // 取得副檔名
        $exts = $exts[$_FILES['img']['type']];
        // 建立隨機檔案名稱
        $file_name = md5($_FILES['img']['name'].uniqid());
        $upload_path = 'photos/'.$file_name.$exts;
        // 將檔案移動到指定資料夾
        if(move_uploaded_file(
          // 暫存檔案的路徑
          $_FILES['img']['tmp_name'],
          $dir.$file_name.$exts
          )) {
            // $output['success']=true; 
            // $output['file']=$file_name.$exts
            // ;
          }
      }
    }else{
      $upload_path=null;
    }
    // -------------------照片END
    # ********* TODO END *************

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $_POST['product_name'],
      $_POST['description'],
      $_POST['price'],
      $_POST['category'],
      $_POST['product_status'],
      $_POST['stock'],
      $upload_path  ?? null,
      $_POST['product_id']
    ]);

  }


  /************** 修改商品類別********************************/ elseif (!empty($_POST['category_name'])) {
    $sql = "UPDATE `categories` SET 
  `category_name`=?,
  `category_tag`=?,
  `category_description`=?
  WHERE `category_id`=? ";



    # ********* TODO: 欄位檢查 *************

    if (empty($_POST['category_name'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫類別名稱!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['category_tag'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫類別標籤!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    }

    # ********* TODO END *************

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $_POST['category_name'],
      $_POST['category_tag'],
      $_POST['description'],
      $_POST['category_id']
    ]);

  }


  /************** 修改訂單資訊********************************/ elseif (!empty($_POST['order_id'])) {
    $sql = "UPDATE `orders` SET 
  `recipient_name`=?,
  `recipient_phone`=?,
  `recipient_email`=?,
  `shipping_address`=?
  WHERE `order_id`=? ";



    # ********* TODO: 欄位檢查 *************

    if (!isset($_POST['recipient_name'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫收件者名稱!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['recipient_phone'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫收件者電話!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['recipient_email'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫收件者信箱!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } elseif (!isset($_POST['shipping_address'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫收件地址!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    }

    # ********* TODO END *************

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $_POST['recipient_name'],
      $_POST['recipient_phone'],
      $_POST['recipient_email'],
      $_POST['shipping_address'],
      $_POST['order_id'],
    ]);

  }



  /************** 修改活動資訊********************************/ elseif (!empty($_POST['promotion_name'])) {
    $sql = "UPDATE `promotions` SET 
  `promotion_name`=?,
  `promotion_description`=?,
  `discount_percentage`=?,
  `start_date`=?,
  `end_date`=?
  WHERE `promotion_id`=? ";



    # ********* TODO: 欄位檢查 *************

    if (empty($_POST['start_date'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫開始日期!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } else if (empty($_POST['end_date'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫結束日期!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } else if (empty($_POST['discount_percentage'])) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '沒有填寫折扣!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    } else if ($_POST['discount_percentage'] < 0 || $_POST['discount_percentage'] > 100) {
      $output['code'] = 401; # 自行決定的除錯編號
      $output['error'] = '折扣 % 格式錯誤!';
      echo json_encode($output, JSON_UNESCAPED_UNICODE);
      exit;
    }



    # ********* TODO END *************

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $_POST['promotion_name'],
      $_POST['description'] ?? null,
      $_POST['discount_percentage'],
      $_POST['start_date'],
      $_POST['end_date'],
      $_POST['promotion_id'],
    ]);

  }


  /************** 編輯促銷活動商品 **********************************************/
  // 編輯促銷活動商品
if (
  isset($_POST['promotion_id']) &&
  (isset($_POST['product_ids']) || isset($_POST['variant_ids']))
) {
  $promotion_id = intval($_POST['promotion_id']);

  $product_ids = $_POST['product_ids'] ?? '[]';
  $variant_ids = $_POST['variant_ids'] ?? '[]';

  $selectedProducts = json_decode($product_ids, true);
  $selectedVariants = json_decode($variant_ids, true);

  if (!is_array($selectedProducts)) $selectedProducts = [];
  if (!is_array($selectedVariants)) $selectedVariants = [];

  try {
      $pdo->beginTransaction();

      // 刪除舊的關聯
      $sql_delete = "DELETE FROM Promotion_Products WHERE promotion_id = ?";
      $stmt = $pdo->prepare($sql_delete);
      $stmt->execute([$promotion_id]);

      // 插入新的關聯
      $sql = "INSERT INTO Promotion_Products (promotion_id, product_id, variant_id) VALUES (?, ?, ?)";
      $stmt = $pdo->prepare($sql);

      foreach ($selectedProducts as $product_id) {
          $stmt->execute([$promotion_id, $product_id, null]);
      }

      foreach ($selectedVariants as $variant_id) {
          $stmt->execute([$promotion_id, null, $variant_id]);
      }

      $pdo->commit();
      $output['success'] = true;
      $output['message'] = '促銷活動商品編輯成功';
      $output['lastInsertId'] = $pdo->lastInsertId();
  } catch (PDOException $e) {
      $pdo->rollBack();
      $output['success'] = false;
      $output['error'] = '資料庫錯誤: ' . $e->getMessage();
  }
} else {
  $output['success'] = false;
  $output['error'] = '缺少必要參數';
}

  $output['success'] = !!$stmt->rowCount(); # 修改了幾筆, 轉布林值




  echo json_encode($output, JSON_UNESCAPED_UNICODE);
}