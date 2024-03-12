<?php
    require_once("config.php");
    class User{
        protected $conn;
            function __construct(){ 
                $dsn = 'mysql:host='.BBDD_HOST.';dbname='.BBDD_NAME.';charset=utf8' ;
                $usuario= BBDD_USER;
                $pass = BBDD_PASSWORD;
                try { 
                    $this->conn = new PDO( $dsn, $usuario, $pass );
                } catch ( PDOException $e) {
                    die( "¡Error!: " . $e->getMessage() . "<br/>");
                }
            }
            public function getConBD() { return $this->conn; }

            function __destruct(){
                $this->conn = null;
            }

            function obtenerImagen ($id_user){
                $stmt = $this->conn->prepare("SELECT photo from user where id=:id");
                $stmt->execute([":id"=>$id_user]);
                $fila = $stmt->fetch();
                return $fila['photo']; 
            }

            function listado_user(){
                $listarUser = $this->conn->query("SELECT id, photo, nickname from user");
                
                echo "
                    <table>
                ";
                while($fila=$listarUser->fetch(PDO::FETCH_ASSOC)) {
                    $photo = ($fila['photo'] == null) ? 'img/imagenDefecto.jpg' : 'imagen.php?uid='.$fila['id'];
                    echo "
                        <tr>
                            <td><img src='$photo' /></td>
                            <td>{$fila['nickname']}</td>
                        </tr>
                    ";
                }
                echo "
                    </table>
                ";
            }

            function crearUser($name, $surname, $email, $nickname, $photo){
                $photoContent = ($photo == null) ? file_get_contents('img/imagenDefecto.jpg') : file_get_contents($photo);
                $stmt = $this->conn->prepare("INSERT INTO user (name, surname, email, nickname, photo) VALUES(:name, :surname, :email, :nickname, :photo)");
                $stmt->execute([":name"=>$name, ":surname"=>$surname, ":email"=>$email, ":nickname"=>$nickname, ":photo"=>$photoContent]);
            }
            
    }    
?>