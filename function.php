<?php
//registration form
function validate_email($email){   
    $emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
    if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email)){
        $_SESSION['e_email']="Podaj poprawny adres email";
        return false;
    }return true;}
function validate_nick($nick){
    if((strlen($nick)<3) || (strlen($nick)>20)){
        $_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków";
        return false;
    }
   if(ctype_alnum($nick)==false){
        $_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr bez polskich znaków";
        return false;
    }return true;}
function validate_double_password($haslo1,$haslo2,$validate_reg_ok):array {
    if((strlen($haslo1)<8) || (strlen($haslo1)>20)){
        $validate_reg_ok = false;
        $_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków";
    }
    if($haslo1!=$haslo2){
        $validate_reg_ok = false;
        $_SESSION['e_haslo']="Podane hasła nie są jednakowe";
    }
    $haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);
    return [$validate_reg_ok,$haslo_hash];}

function validate_policy(){
    if(!isset($_POST['regulamin'])){
        $_SESSION['e_regulamin']="Regulamin nie został zaakceptowany";
        return false;
    }return true;}
function validate_reCaptcha(){
    $sekret = "6LdwJy8rAAAAAGFhrGz5VT7cBxVT0FHhjFn8v4yC";
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
    $odpowiedz = json_decode($sprawdz);
    if($odpowiedz->success==false){
        $_SESSION['e_bot']="Potwierdź czy nie jesteś botem"; 
        return false;
    }return true;
}
function check_email_exist($connection, $email) {
    $stmt = $connection->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        throw new Exception($connection->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $rezultat = $stmt->get_result();
    if (!$rezultat) {
        $stmt->close();
        throw new Exception($connection->error);
    }

    if ($rezultat->num_rows > 0) {
        $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu email";
        return false;
    }

    $stmt->close();
    return true;
 }
function check_nick_exist($connection, $nick) {
    $stmt = $connection->prepare("SELECT id FROM users WHERE username = ?");
    if (!$stmt) {
        throw new Exception($connection->error);
    }

    $stmt->bind_param("s", $nick);
    $stmt->execute();

    $rezultat = $stmt->get_result();
    if (!$rezultat) {
        $stmt->close();
        throw new Exception($connection->error);
    }

    if ($rezultat->num_rows > 0) {
        $_SESSION['e_nick'] = "Istnieje już konto o takim nicku, podaj inny login";
        return false;
    }

    $stmt->close();
    return true;
}
function remembering_entered_nick(){
    if(isset($_SESSION['fr_nick'])){
        echo $_SESSION['fr_nick'];
        unset($_SESSION['fr_nick']); 
    }
    }
function remembering_entered_email(){
    if(isset($_SESSION['fr_email'])){
    echo $_SESSION['fr_email'];
    unset($_SESSION['fr_email']); 
    }
    }
function remembering_entered_pass1(){
    if(isset($_SESSION['fr_haslo1'])){
        echo $_SESSION['fr_haslo1'];
        unset($_SESSION['fr_haslo1']); 
    }
    }
function remembering_entered_pass2(){
    if(isset($_SESSION['fr_haslo2'])){
        echo $_SESSION['fr_haslo2'];
        unset($_SESSION['fr_haslo2']); 
    }
    }
function remembering_statute(){
    if(isset($_SESSION['fr_regulamin'])){
        echo "checked";
        unset($_SESSION['fr_regulamin']); 
    }
}
function err_entered_nick(){
    if(isset($_SESSION['e_nick'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_nick'].'</div>';
        unset($_SESSION['e_nick']);
    }
    }
function err_entered_email(){
    if(isset($_SESSION['e_email'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_email'].'</div>';
        unset($_SESSION['e_email']);
    }
    }
function err_entered_pass1(){
    if(isset($_SESSION['e_haslo'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_haslo'].'</div>';
        unset($_SESSION['e_haslo']);
    }
    }
function err_statute(){
    if(isset($_SESSION['e_regulamin'])){
       echo '<div class="text-danger small mt-1">'.$_SESSION['e_regulamin'].'</div>';
        unset($_SESSION['e_regulamin']);
    }
    }
function err_reCaptcha(){
    if(isset($_SESSION['e_bot'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_bot'].'</div>';
        unset($_SESSION['e_bot']);
    }
}
function insert_registerUser_toDB($connection,$nick,$haslo_hash,$email){     
    $stmt = $connection->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if(!$stmt){
        throw new Exception($connection->error);
    }
    $stmt->bind_param("sss", $nick, $haslo_hash, $email);
    if($stmt->execute()){
        $_SESSION['udanarejestracja']=true;
        header('Location: login.php');
        exit();
    }else{
        throw new Exception($stmt->error);
    }
    $stmt->close();
} 
function registration_form_with_policy_and_reCaptcha(){
    if(isset($_POST['email'])){
        $validate_reg_ok=true;

        $nick = $_POST['nick'];
        $validate_reg_ok=validate_nick($nick);

        $email = $_POST['email'];
        $validate_reg_ok=validate_email($email);

        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];
        [$validate_reg_ok,$haslo_hash]=validate_double_password($haslo1,$haslo2,$validate_reg_ok);

        $validate_reg_ok=validate_policy();
        $validate_reg_ok=validate_reCaptcha();

        $_SESSION['fr_nick'] = $nick;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;

    if(isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;
    require_once "connect.php";
    try{
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if($connection->connect_errno!=0){
            throw new Exception(mysqli_connect_errno());
        }else{
            $validate_reg_ok=check_email_exist($connection,$email);
            $validate_reg_ok=check_nick_exist($connection,$nick);
            if($validate_reg_ok == true){     
                insert_registerUser_toDB($connection,$nick,$haslo_hash,$email);
            }  
            $connection->close();
        }
    }catch(Exception $e){
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności</span>';
        //echo '</br>Informacja deweloperska: '.$e;
    }
    }
    }
function registration_form_without_policy_and_reCaptcha(){
    if(isset($_POST['email'])){
        $validate_reg_ok=true;

        $nick = $_POST['nick'];
        $validate_reg_ok=validate_nick($nick);

        $email = $_POST['email'];
        $validate_reg_ok=validate_email($email);

        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];
        [$validate_reg_ok,$haslo_hash]=validate_double_password($haslo1,$haslo2,$validate_reg_ok);

        $_SESSION['fr_nick'] = $nick;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;

    if($validate_reg_ok === true){
        require_once "connect.php";
        try{
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
            if($connection->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }else{
                $validate_reg_ok=check_email_exist($connection,$email);
                $validate_reg_ok=check_nick_exist($connection,$nick);
                if($validate_reg_ok == true){     
                    insert_registerUser_toDB($connection,$nick,$haslo_hash,$email);
                }  
                $connection->close();
            }
        }catch(Exception $e){
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności</span>';
            //echo '</br>Informacja deweloperska: '.$e;
    }
    }
    }
}

//login form
function remembering_entered_logiin(){
    if(isset($_SESSION['fr_login'])){
        echo $_SESSION['fr_login'];
        unset($_SESSION['fr_login']); 
    }
}
function err_password(){
    if(isset($_SESSION['bladhaslo'])){
    echo '<div class="text-danger small mt-1">'.$_SESSION['bladhaslo'].'</div>';
    unset($_SESSION['bladhaslo']);
    }
 }
function err_login(){
    if(isset($_SESSION['bladlogin'])){
    echo '<div class="text-danger small mt-1">'.$_SESSION['bladlogin'].'</div>';
    unset($_SESSION['bladlogin']);
    }
}
function login_form_check_and_log(){
    require_once "connect.php";
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if($connection->connect_errno != 0){
            throw new Exception($connection->connect_errno);
        } else {
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            $_SESSION['fr_login'] = $login; 

            $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $rezultat = $stmt->get_result();

            if($rezultat->num_rows > 0){
                $wiersz = $rezultat->fetch_assoc();
                
                if(password_verify($haslo, $wiersz['password'])) {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['id'] = $wiersz['id'];
                    $_SESSION['username'] = $wiersz['username'];

                    $stmt->close();
                    $connection->close();

                    header('Location: home.php');
                    exit();
                } else {
                    $_SESSION['bladhaslo'] = '<span style="color:red">Nieprawidłowe hasło!</span>';
                    header('Location: login.php');
                    exit();
                }
            } else {
                $_SESSION['bladlogin'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                header('Location: login.php');
                exit();
            }
        }
    } catch(Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności</span>';
        echo '</br>Informacja deweloperska: '.$e;
    }
}

//add expense form
function validate_amount($amount):array{
    $amount = str_replace(',', '.', $amount);
    $amount = floatval($amount);
    if($amount <= 0){
        $_SESSION['e_amount'] = 'Wpisz poprawną kwotę ';
        return [false,$amount];
    }
    return [true,$amount];
 }
function validate_comment($comment){
    if((strlen($comment)<3) || (strlen($comment)>100)){
        $_SESSION['e_comment']="Komentarz musi posiadać od 3 do 100 znaków";
        return false;
    }
    return true;
}
function check_selection_payment_type(){
    if (!isset($_POST['payment_type']) && $_POST['payment_type'] !== '') {
        $_SESSION['e_payment'] = 'Musisz wybrać metodę płatności.';
        return false;
    }
    return true;
 }
function check_selection_expense_type(){
    if (!isset($_POST['expense_type']) && $_POST['expense_type'] !== '') {
        $_SESSION['e_expense'] = 'Musisz wybrać typ wydatku.';
        return false;
    }
    return true;
}
function remembering_entered_amount(){
    if(isset($_SESSION['fr_amount'])){
        echo $_SESSION['fr_amount'];
        unset($_SESSION['fr_amount']); 
    }
    }
function remembering_entered_date(){
    if(isset($_SESSION['fr_date'])){
        echo $_SESSION['fr_date'];
        unset($_SESSION['fr_date']); 
    }else{
        echo date('Y-m-d'); 
    }
    }
function remembering_entered_expense_type(){
    if(isset($_SESSION['fr_expense_type'])){
        echo $_SESSION['fr_expense_type'];
        unset($_SESSION['fr_expense_type']); 
    }
    }
function remembering_entered_payment_type(){
    if(isset($_SESSION['fr_payment_type'])){
        echo $_SESSION['fr_payment_type'];
        unset($_SESSION['fr_payment_type']); 
    }
    }
function remembering_entered_comment(){
    if(isset($_SESSION['fr_comment'])){
        echo $_SESSION['fr_comment'];
        unset($_SESSION['fr_comment']); 
    }
}
function err_entered_comment(){
        if(isset($_SESSION['e_comment'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_comment'].'</div>';
        unset($_SESSION['e_comment']);
    }
 }
function err_entered_amount(){
        if(isset($_SESSION['e_amount'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_amount'].'</div>';
        unset($_SESSION['e_amount']);
    }
 }
function err_entered_payment(){
        if(isset($_SESSION['e_payment'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_payment'].'</div>';
        unset($_SESSION['e_payment']);
    }
 }
function err_entered_expense(){
        if(isset($_SESSION['e_expense'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_expense'].'</div>';
        unset($_SESSION['e_expense']);
    }
}
function after_form_execute(){
    unset($_SESSION['fr_amount']);
    unset($_SESSION['fr_date']);
    unset($_SESSION['fr_expense_type']);
    unset($_SESSION['fr_income_type']);
    unset($_SESSION['fr_payment_type']);
    unset($_SESSION['fr_comment']);
}
function load_payment_type_fromDB(){
    require 'connect.php';

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno) {
        die("Błąd połączenia z bazą: " . $connection->connect_error);
    }

    $query = "SELECT id, name FROM payment_methods_default ORDER BY name ASC"; 
    $result = $connection->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
        $result->free();
    } else {
        echo '<option value="">Brak danych</option>';
    }
    $connection->close();
 }
function load_expense_type_fromDB(){
    require 'connect.php';

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno) {
        die("Błąd połączenia z bazą: " . $connection->connect_error);
    }

    $query = "SELECT id, name FROM expenses_category_default ORDER BY name ASC"; 
    $result = $connection->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
        $result->free();
    } else {
        echo '<option value="">Brak danych</option>';
    }
    $connection->close();
 }

function add_user_expense_toDB() {
    if (isset($_POST['date'])) {       
        $userid = $_SESSION['id'];
        $validate_exp_ok = true;

        $amount = $_POST['amount'];
        [$validate_exp_ok,$amount] = validate_amount($amount);

        $payment_method = $_POST['payment_type'];
        $validate_exp_ok = check_selection_payment_type();

        $expense_category = $_POST['expense_type'];
        $validate_exp_ok = check_selection_expense_type();
        
        $date = $_POST['date'];
        $comment = $_POST['comment'] ?? '';
        $validate_exp_ok = validate_comment($comment);

        $_SESSION['fr_amount'] = $amount;
        $_SESSION['fr_date'] = $date;
        $_SESSION['fr_expense_type'] = $expense_category;
        $_SESSION['fr_payment_type'] = $payment_method;
        $_SESSION['fr_comment'] = $comment;
        
        if($validate_exp_ok == true){
            require 'connect.php';
            try {
                $connection = new mysqli($host, $db_user, $db_password, $db_name);
                if ($connection->connect_errno) {
                    throw new Exception("Błąd połączenia z bazą: " . $connection->connect_error);
                }

                $stmt = $connection->prepare(
                    "INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                if (!$stmt) {
                    throw new Exception("Błąd przygotowania zapytania: " . $connection->error);
                }

                $stmt->bind_param("issdss", $userid, $expense_category, $payment_method, $amount, $date, $comment);
                
                if ($stmt->execute()) {
                    after_form_execute();
                    header('Location: addExpenses.php');
                    exit();
                } else {
                    throw new Exception("Błąd wykonania zapytania: " . $stmt->error);
                }

                $stmt->close();
                $connection->close();

            } catch (Exception $e) {
                echo '<span style="color: red;">Wystąpił błąd: ' . $e->getMessage() . '</span>';
            }
        }else{
            header('Location: addExpenses.php');
            exit();
        }
    }
}

//add income form
function check_selection_income_type(){
    if (!isset($_POST['income_type']) && $_POST['income_type'] !== '') {
        $_SESSION['e_income'] = 'Musisz wybrać typ przychodu.';
        return false;
    }
    return true;
}
function err_entered_income(){
        if(isset($_SESSION['e_income'])){
        echo '<div class="text-danger small mt-1">'.$_SESSION['e_income'].'</div>';
        unset($_SESSION['e_income']);
    }
}
function load_income_type_fromDB(){
    require 'connect.php';

    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno) {
        die("Błąd połączenia z bazą: " . $connection->connect_error);
    }

    $query = "SELECT id, name FROM incomes_category_default ORDER BY name ASC"; 
    $result = $connection->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
        $result->free();
    } else {
        echo '<option value="">Brak danych</option>';
    }
    $connection->close();
 }
function add_user_income_toDB(){
    if (isset($_POST['date'])) {       
        $userid = $_SESSION['id'];
        $validate_inc_ok = true;

        $amount = $_POST['amount'];
        [$validate_inc_ok,$amount] = validate_amount($amount);

        $income_category = $_POST['income_type'];
        $validate_inc_ok = check_selection_income_type();
        
        $date = $_POST['date'];
        $comment = $_POST['comment'] ?? '';
        $validate_inc_ok = validate_comment($comment);

        $_SESSION['fr_amount'] = $amount;
        $_SESSION['fr_date'] = $date;
        $_SESSION['fr_income_type'] = $income_category;
        $_SESSION['fr_comment'] = $comment;
        
        if($validate_inc_ok == true){
            require 'connect.php';
            try {
                $connection = new mysqli($host, $db_user, $db_password, $db_name);
                if ($connection->connect_errno) {
                    throw new Exception("Błąd połączenia z bazą: " . $connection->connect_error);
                }

                $stmt = $connection->prepare(
                    "INSERT INTO incomes (user_id, income_category_assigned_to_user_id,  amount, date_of_income, income_comment)
                    VALUES (?, ?, ?, ?, ?)"
                );

                if (!$stmt) {
                    throw new Exception("Błąd przygotowania zapytania: " . $connection->error);
                }

                $stmt->bind_param("isdss", $userid, $income_category, $amount, $date, $comment);
                
                if ($stmt->execute()) {
                    after_form_execute();
                    header('Location: addIncomes.php');
                    exit();
                } else {
                    throw new Exception("Błąd wykonania zapytania: " . $stmt->error);
                }

                $stmt->close();
                $connection->close();

            } catch (Exception $e) {
                echo '<span style="color: red;">Wystąpił błąd: ' . $e->getMessage() . '</span>';
            }
        }else{
            header('Location: addIncomes.php');
            exit();
        }
    }
}

//financial balance
function remembering_entered_start_date(){
    if(isset($_SESSION['start_date'])){
        echo $_SESSION['start_date']; 
    }
    }

function remembering_entered_end_date(){
    if(isset($_SESSION['end_date'])){
        echo $_SESSION['end_date'];  
    }
}
function load_user_financial_balance_fromDB_to_pichart(){

 }
function load_user_financial_balance_fromDB_to_list() {
    $start = $_SESSION['start_date'];
    $end = $_SESSION['end_date'];
    $userId = $_SESSION['id'];

    require 'connect.php';
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno) {
        die("Błąd połączenia z bazą: " . $connection->connect_error);
    }

    $sql = "SELECT 
                (SELECT COALESCE(SUM(amount), 0) 
                 FROM incomes 
                 WHERE user_id = ? AND date_of_income BETWEEN ? AND ?) AS przychody, 

                (SELECT COALESCE(SUM(amount), 0) 
                 FROM expenses 
                 WHERE user_id = ? AND date_of_expense BETWEEN ? AND ?) AS wydatki, 

                (SELECT COALESCE(SUM(amount), 0) 
                 FROM incomes 
                 WHERE user_id = ? AND date_of_income BETWEEN ? AND ?) - 
                (SELECT COALESCE(SUM(amount), 0) 
                 FROM expenses 
                 WHERE user_id = ? AND date_of_expense BETWEEN ? AND ?) AS bilans";

    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $connection->error);
    }

    $stmt->bind_param("ississississ", 
        $userId, $start, $end,
        $userId, $start, $end,
        $userId, $start, $end,
        $userId, $start, $end
    );

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Błąd wykonania zapytania: " . $connection->error);
    }

    $row = $result->fetch_assoc();
    $suma_przychodow = $row['przychody'];
    $suma_wydatkow = $row['wydatki'];
    $bilans = $row['bilans'];

    echo '<ul class="fs-6" style="list-style-type: none;">';
        echo "<li><hr></li>";
        echo "<li>Przychody: {$suma_przychodow} zł</li>";
        echo "<li><hr></li>";
        echo "<li>Wydatki: {$suma_wydatkow} zł</li>";
        echo "<li><hr></li>";
        echo "<li>Bilans: {$bilans} zł</li>";
        echo "<li><hr></li>";
        if($bilans > 0){
            echo "<li>Gratulacje Mistrzu Oszczędzania</li>";
        }else if($bilans < 0){
            echo "<li>Musisz popracować nad wydatkami</li>";
        }else{
            echo "<li>Wyszedłeś na 0, nie taki jest Twój cel</li>";
        }
    echo '</ul>';

    $stmt->close();
    $connection->close();
}
function load_user_incomes_fromDB_to_pichart(){
    $start = $_SESSION['start_date'];
    $end = $_SESSION['end_date'];
    $userId = $_SESSION['id'];

    require 'connect.php';
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno) {
        die(json_encode(["error" => "Błąd połączenia z bazą: " . $connection->connect_error]));
    }

    $sql = "SELECT 
                icd.name AS category,
                SUM(i.amount) AS total_amount
            FROM incomes AS i
            INNER JOIN incomes_category_default AS icd ON icd.id = i.income_category_assigned_to_user_id
            WHERE 
                i.user_id = ? 
                AND i.date_of_income BETWEEN ? AND ?
            GROUP BY icd.name
            ORDER BY total_amount DESC";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $userId, $start, $end);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if (!$result) {
        return (["error" => "Błąd zapytania: " . $connection->error]);
    }

    $data = [];
    
    $data[] = ['Kategoria', 'Kwota'];

    while ($row = $result->fetch_assoc()) {
         $data[] = [$row['category'], (float)$row['total_amount']];
    }

    $stmt->close();
    $connection->close();

    return $data;
 }
function load_user_expenses_fromDB_to_pichart(){
    $start = $_SESSION['start_date'];
    $end = $_SESSION['end_date'];
    $userId = $_SESSION['id'];

    require 'connect.php';
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno) {
        die(json_encode(["error" => "Błąd połączenia z bazą: " . $connection->connect_error]));
    }

    $sql = "SELECT 
                ecd.name AS category,
                SUM(e.amount) AS total_amount
            FROM expenses AS e
            INNER JOIN expenses_category_default AS ecd ON ecd.id = e.expense_category_assigned_to_user_id
            WHERE 
                e.user_id = ? 
                AND e.date_of_expense BETWEEN ? AND ?
            GROUP BY ecd.name
            ORDER BY total_amount DESC";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $userId, $start, $end);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if (!$result) {
        return (["error" => "Błąd zapytania: " . $connection->error]);
    }

    $data = [];
    
    $data[] = ['Kategoria', 'Kwota'];

    while ($row = $result->fetch_assoc()) {
         $data[] = [$row['category'], (float)$row['total_amount']];
    }

    $stmt->close();
    $connection->close();

    return $data;
 }
function load_user_incomes_fromDB_to_list() {
    $start = $_SESSION['start_date'];
    $end = $_SESSION['end_date'];
    $userId = $_SESSION['id'];

    require 'connect.php';
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno) {
        die("Błąd połączenia z bazą: " . $connection->connect_error);
    }

    $sql = "SELECT
                icd.name AS category,
                i.amount,
                i.date_of_income,
                i.income_comment
            FROM incomes AS i
            INNER JOIN incomes_category_default AS icd ON icd.id = i.income_category_assigned_to_user_id
            WHERE i.user_id = ? AND i.date_of_income BETWEEN ? AND ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $userId, $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Błąd zapytania: " . $connection->error);
    }

    $incomesByCategory = [];

    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $item = $row['amount'] . ' zł - ' . $row['date_of_income'] . ' - ' . $row['income_comment'];
        $incomesByCategory[$category][] = $item;
    }

    echo '<ul class="fs-6">';
    foreach ($incomesByCategory as $category => $items) {
        echo "<li>$category<ul>";
        foreach ($items as $entry) {
            echo "<li>$entry</li>";
        }
        echo "<li style='list-style-type: none;'><hr></li>";
        echo '</ul></li>';
    }
    echo '</ul>';
    $stmt->close();
    $connection->close();
 }
function load_user_expenses_fromDB_to_list(){
    $start = $_SESSION['start_date'];
    $end = $_SESSION['end_date'];
    $userId = $_SESSION['id'];

    require 'connect.php';
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno) {
        die("Błąd połączenia z bazą: " . $connection->connect_error);
    }

    $sql = "SELECT
                ecd.name AS category,
                e.amount,
                e.date_of_expense,
                e.expense_comment
            FROM expenses AS e
            INNER JOIN expenses_category_default AS ecd ON ecd.id = e.expense_category_assigned_to_user_id
            WHERE e.user_id = ? AND e.date_of_expense BETWEEN ? AND ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $userId, $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Błąd zapytania: " . $connection->error);
    }

    $expensesByCategory = [];

    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $item = $row['amount'] . ' zł - ' . $row['date_of_expense'] . ' - ' . $row['expense_comment'];
        $expensesByCategory[$category][] = $item;
    }

    echo '<ul class="fs-6">';
    foreach ($expensesByCategory as $category => $items) {
        echo "<li>$category<ul>";
        foreach ($items as $entry) {
            echo "<li>$entry</li>";
        }
        echo "<li style='list-style-type: none;'><hr></li>";
        echo '</ul></li>';
    }
    echo '</ul>';

    $stmt->close();
    $connection->close();
}
function set_date(){
        
    if (isset($_POST['date_start']) && isset($_POST['date_end'])) {
        $start = $_POST['date_start'];
        $end = $_POST['date_end'];
        $_SESSION['start_date'] = $start;
        $_SESSION['end_date'] = $end;
    }else{
        $start = date('Y-m-d', strtotime('-1 month'));
        $end = date('Y-m-d');
        $_SESSION['start_date'] = $start;
        $_SESSION['end_date'] = $end;
    }
}


?>
