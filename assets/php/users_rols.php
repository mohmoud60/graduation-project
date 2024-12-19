<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);




if ($action === "listing_users") {
    $draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
    $search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
    $order_column = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 0;
    $order_dir = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';

    $columns = ['Employee_id', 'CreatedDate', 'Last_login', 'role_name', 'Employee_FullName', 'Employee_Email', 'avatar_path'];
    $order_column_name = $columns[$order_column];
    $params = [];
    $search_cond = 'WHERE a.Delete_Date IS NULL';

    if ($search != '') {
        $search_cond .= " AND (e.Employee_FullName LIKE :search OR e.Employee_Email LIKE :search OR r.name LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // استعلام لحساب العدد الكلي
    $query = $conn->prepare("SELECT COUNT(*) as total FROM account a
        LEFT JOIN employee e ON e.Employee_id = a.Employee_id
        LEFT JOIN roles r ON r.id = a.role_id
        $search_cond");
    $query->execute($params);
    $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

    // جلب البيانات المطلوبة
    $query = $conn->prepare("
        SELECT 
            a.Employee_id,
            a.CreatedDate,
            a.Last_login,
            a.role_id,
            e.Employee_FullName,
            e.Employee_Email,
            e.avatar_path,
            r.description AS role_name
        FROM account a
        LEFT JOIN employee e ON e.Employee_id = a.Employee_id
        LEFT JOIN roles r ON r.id = a.role_id
        $search_cond
        ORDER BY $order_column_name $order_dir
        LIMIT $start, $length
    ");
    $query->execute($params);

    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    $result = array(
        "draw" => $draw,
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data
    );

    echo json_encode($result);
}

if ($action === "listing_rols") {
    // جلب بيانات الأدوار مع الصلاحيات وعدد المستخدمين
    $query = "
    SELECT 
        roles.id AS role_id, 
        roles.name AS role_name, 
        roles.description AS role_description,
        (
            SELECT COUNT(*) 
            FROM account 
            WHERE account.role_id = roles.id AND account.Delete_Date IS NULL
        ) AS total_users,
        GROUP_CONCAT(permissions.description SEPARATOR ', ') AS permissions
    FROM roles
    LEFT JOIN role_permissions ON role_permissions.role_id = roles.id AND role_permissions.Delete_Date IS NULL
    LEFT JOIN permissions ON permissions.id = role_permissions.permission_id AND permissions.Delete_Date IS NULL
    WHERE roles.Delete_Date IS NULL
    GROUP BY roles.id
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($roles);
}

if ($action === "get_permissions") {

    $query = "SELECT id, name, description FROM permissions WHERE Delete_Date IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($permissions);

}

if ($action === "add_role") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['role_name']) || empty($data['role_name'])) {
        echo json_encode(['success' => false, 'message' => 'الرجاء إدخال اسم الدور.']);
        exit;
    }

    $role_description = $data['role_name']; // الاسم العربي المدخل
    $permissions = isset($data['permissions']) ? $data['permissions'] : [];

    try {
        // حساب عدد الأدوار الحالية لتوليد name
        $query = "SELECT COUNT(*) AS count_id FROM roles";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count_id = $result['count_id'] + 1; // زيادة العدد بمقدار 1

        // توليد name بالشكل role_<count_id>
        $role_name = "role_" . $count_id;

        // إدخال الدور الجديد
        $query = "INSERT INTO roles (name, description) VALUES (:name, :description)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':name' => $role_name,
            ':description' => $role_description,
        ]);
        $role_id = $conn->lastInsertId();

        // ربط الصلاحيات بالدور
        foreach ($permissions as $permission_id) {
            $query = "INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':role_id' => $role_id,
                ':permission_id' => $permission_id,
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'تمت إضافة الدور بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء الإضافة: ' . $e->getMessage()]);
    }
}

if ($action === "get_role_permissions") {
    $role_id = $_GET['role_id'];

    $query = "
    SELECT 
        permissions.id, 
        permissions.description,
        CASE 
            WHEN role_permissions.permission_id IS NOT NULL THEN 1 
            ELSE 0 
        END AS assigned
    FROM permissions
    LEFT JOIN role_permissions ON role_permissions.permission_id = permissions.id AND role_permissions.role_id = :role_id
    WHERE permissions.Delete_Date IS NULL
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([':role_id' => $role_id]);
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['permissions' => $permissions]);
}

if ($action === "update_role_permissions") {
    $data = json_decode(file_get_contents("php://input"), true);

    // التحقق من وجود role_id
    if (!isset($data['role_id']) || empty($data['role_id'])) {
        echo json_encode(['success' => false, 'message' => 'معرف الدور غير موجود.']);
        exit;
    }

    $role_id = $data['role_id'];
    $permissions = isset($data['permissions']) ? $data['permissions'] : [];

    try {
        // حذف الصلاحيات القديمة
        $query = "DELETE FROM role_permissions WHERE role_id = :role_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':role_id' => $role_id]);

        // إدخال الصلاحيات الجديدة
        foreach ($permissions as $permission_id) {
            $query = "INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':role_id' => $role_id,
                ':permission_id' => $permission_id,
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'تم تحديث الصلاحيات بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء التحديث: ' . $e->getMessage()]);
    }
}

if ($action === "update_user_role") {
    $data = json_decode(file_get_contents("php://input"), true);
    $employee_id = $data['employee_id'];
    $role_id = $data['role_id'];

    try {
        $query = "UPDATE account SET role_id = :role_id WHERE Employee_id = :employee_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':role_id' => $role_id, ':employee_id' => $employee_id]);

        echo json_encode(['success' => true, 'message' => 'تم تحديث صلاحيات المستخدم بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء تحديث الصلاحيات: ' . $e->getMessage()]);
    }
}


if ($action === "get_user_role_permissions") {
    // التحقق من الجلسة للحصول على employee_id
    $employee_id = $_SESSION['employee_id'] ?? null;

    if (!$employee_id) {
        echo json_encode(['success' => false, 'message' => 'معرف الموظف غير موجود في الجلسة.']);
        exit;
    }

    try {
        $query = "
            SELECT 
                roles.id AS role_id, 
                roles.name AS role_name, 
                roles.description AS role_description, 
                1 AS assigned, -- الدور المخصص للمستخدم فقط
                GROUP_CONCAT(permissions.name) AS permissions,
                employee.Employee_FullName AS user_name
            FROM roles
            JOIN account ON account.role_id = roles.id -- تحديد الأدوار المخصصة فقط
            LEFT JOIN role_permissions ON role_permissions.role_id = roles.id
            LEFT JOIN permissions ON permissions.id = role_permissions.permission_id
            LEFT JOIN employee ON employee.Employee_id = account.Employee_id
            WHERE account.Employee_id = :employee_id AND roles.Delete_Date IS NULL
            GROUP BY roles.id, employee.Employee_FullName
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([':employee_id' => $employee_id]);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($roles && isset($roles[0]['user_name'])) {
            echo json_encode([
                'success' => true,
                'user_name' => $roles[0]['user_name'], // اسم المستخدم
                'roles' => $roles
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'لا توجد أدوار مخصصة لهذا المستخدم.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء جلب البيانات: ' . $e->getMessage()]);
    }
}


if ($action === "get_user_role_per") {
    $employee_id = $_GET['employee_id'];

    $query = "
        SELECT 
            roles.id, 
            roles.name, 
            roles.description, 
            CASE WHEN account.role_id = roles.id THEN 1 ELSE 0 END AS assigned,
            employee.Employee_FullName AS user_name
        FROM roles
        LEFT JOIN account ON account.role_id = roles.id AND account.Employee_id = :employee_id
        LEFT JOIN employee ON employee.Employee_id = :employee_id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute([':employee_id' => $employee_id]);
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($roles) {
        // جلب اسم المستخدم من أول سجل
        $user_name = $roles[0]['user_name'] ?? "اسم المستخدم غير متوفر";

        echo json_encode(['success' => true, 'user_name' => $user_name, 'roles' => $roles]);
    } else {
        echo json_encode(['success' => false, 'message' => 'لا توجد صلاحيات متوفرة.']);
    }
}



if ($action === "get_permission") {
    $query = "
        SELECT 
            permissions.id AS permission_id, 
            permissions.description AS permission_name,
            permissions.created_at,
            GROUP_CONCAT(CONCAT(roles.id, ':', roles.description) SEPARATOR ', ') AS roles
        FROM permissions
        LEFT JOIN role_permissions ON role_permissions.permission_id = permissions.id
        LEFT JOIN roles ON roles.id = role_permissions.role_id
        GROUP BY permissions.id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($permissions) {
        echo json_encode(['success' => true, 'permissions' => $permissions]);
    } else {
        echo json_encode(['success' => false, 'message' => 'لا توجد بيانات متاحة.']);
    }
}


if ($action === "add_permission") {
    $data = json_decode(file_get_contents("php://input"), true);
    $permission_description = $data['permission_name'];
    $is_core = isset($data['permissions_core']) && $data['permissions_core'] ? 1 : 0;

    try {
        // الحصول على العدد الحالي من الأذونات لتوليد الاسم
        $query = "SELECT COUNT(*) AS total_permissions FROM permissions";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $result['total_permissions'] + 1; // العدد الحالي + 1
        $permission_name = "permission_" . $count;

        // إدخال الإذن الجديد
        $query = "INSERT INTO permissions (name, description, is_core, created_at) VALUES (:name, :description, :is_core, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':name' => $permission_name,
            ':description' => $permission_description,
            ':is_core' => $is_core,
        ]);

        echo json_encode(['success' => true, 'message' => 'تمت إضافة الإذن بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء الإضافة: ' . $e->getMessage()]);
    }
}



if ($action === "update_permission") {
    $data = json_decode(file_get_contents("php://input"), true);
    $permission_id = $data['permission_id'];
    $permission_name = $data['permission_name'];

    // التحقق من أن الإذن ليس أساسيًا
    $query = "SELECT is_core FROM permissions WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $permission_id]);
    $permission = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($permission['is_core']) {
        echo json_encode(['success' => false, 'message' => 'لا يمكن تعديل إذن أساسي.']);
        exit;
    }

    try {
        $query = "UPDATE permissions SET name = :name WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':name' => $permission_name, ':id' => $permission_id]);

        echo json_encode(['success' => true, 'message' => 'تم تعديل الإذن بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء التحديث: ' . $e->getMessage()]);
    }
}


if ($action === "delete_permission") {
    $permission_id = $_GET['permission_id'];

    try {
        $query = "DELETE FROM permissions WHERE id = :permission_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':permission_id' => $permission_id]);

        echo json_encode(['success' => true, 'message' => 'تم حذف الإذن بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ أثناء الحذف: ' . $e->getMessage()]);
    }
    exit; // تأكد من إنهاء السكربت
}




?>
