<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/php/dotenv.php";

class MySQL {
    protected $conn;

    public function __construct() {
        $host = getenv("MYSQL_HOST");
        $database = getenv("MYSQL_DATABASE");
        $port = getenv("MYSQL_PORT");
        $username = getenv("MYSQL_USERNAME");
        $password = getenv("MYSQL_PASSWORD");

        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function getAllProducts() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM product");
            $stmt->execute();
            $products = $stmt->fetchAll();
            foreach ($products as &$product) {
                if (!empty($product)) {
                    $product["description"] = json_decode($product["description"]); // Description column is stored as JSON string in DB
                }
            }
            return $products;
        } catch (\Throwable $e) {
            throw $e;
        } 
    }

    public function getAllProductsJson() {
        try {
            $products = $this->getAllProducts();
            return json_encode($products);
        } catch (\Throwable $e) {
            throw $e;
        } 
    }

    public function getProductById(int $id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM product WHERE id=?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            if (!empty($product)) {
                $product["description"] = json_decode($product["description"]); // Description column is stored as JSON string in DB
            }
            return $product;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getProductJsonById(int $id) {
        try {
            $product = $this->getProductById($id);
            return json_encode($product);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getUserByEmail(string $email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM user WHERE email=?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            return $user;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function createUser(string $email, string $name, string $password) {
        try {
            $this->conn->beginTransaction();

            $user_stmt = $this->conn->prepare("INSERT INTO `storefront`.`user` (`email`, `name`) VALUES (?, ?)");
            $user_stmt->execute([$email, $name]);

            $user_id = $this->conn->lastInsertId();

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $user_password_stmt = $this->conn->prepare("INSERT INTO `storefront`.`user_password` (`user_id`, `hash`) VALUES (?, ?)");
            $user_password_stmt->execute([$user_id, $password_hash]);

            $user_preference_stmt = $this->conn->prepare("INSERT INTO `storefront`.`user_preference` (`user_id`) VALUES (?)");
            $user_preference_stmt->execute([$user_id]);

            $this->conn->commit();
        } catch (\Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>