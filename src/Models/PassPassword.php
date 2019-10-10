<?php


class PassPassword extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("pass_password","pass_password_id", $connection);
    }

    public function GetById(int $id) : Result {
        $res = new Result();
        try{
            $sql = "SELECT pp.*, pf.name as folder_name FROM pass_password as pp
                    LEFT JOIN pass_folder pf on pp.pass_folder_id = pf.pass_folder_id
                    WHERE pass_password_id = :pass_password_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":pass_password_id"=>$id]);

            $res->result = $stmt->fetch();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function Scroll($page, $search){
        $res = new Result();
        $res->current = 1;
        $res->total = 0;
        $res->more = false;

        try{
            $pageCount = 20;
            $offset = ($page - 1) * $pageCount;

            // Total pages
            $totalRows = $this->db->query("SELECT COUNT(*) FROM pass_password as pp
                        INNER JOIN pass_folder pf on pp.pass_folder_id = pf.pass_folder_id
                        WHERE pf.name LIKE '%" . $search . "%' OR pp.title LIKE '%" . $search . "%' ")->fetchColumn();
            $totalPages = ceil($totalRows / $pageCount);

            // Query by pages
            $sql = "SELECT pp.*, pf.name as folder_name, pp.last_update as last_update FROM pass_password as pp 
                    INNER JOIN pass_folder pf on pp.pass_folder_id = pf.pass_folder_id
                    WHERE pf.name LIKE :name OR pp.title LIKE :title LIMIT $offset, $pageCount";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => '%' . $search . '%',
                ':title' => '%' . $search . '%',
            ]);
            $data = $stmt->fetchAll();

            // Return data
            $res->current = $page;
            $res->total = $totalPages;
            $res->result = $data;
            $res->more = ($pageCount * $page) < $totalRows;

            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function Insert($passPassword, $userId){
        $res = new Result();
        try{
            $this->db->beginTransaction();
            $currentDate = date('Y-m-d H:i:s');

            $sql = "SELECT * FROM pass_folder WHERE name = :name LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":name" => $passPassword['folderName']]);
            $folderData = $stmt->fetch();

            $passFolderId = 0;
            if (($folderData['pass_folder_id'] ?? 0 ) >=1 ){
                $passFolderId = $folderData['pass_folder_id'];
            } else {
                $sql = "INSERT INTO pass_folder (updated_at, created_at, created_user_id, updated_user_id, name, description)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :name, :description)";

                $stmt = $this->db->prepare($sql);
                if(!$stmt->execute([
                    ":updated_at" => $currentDate,
                    ":created_at" => $currentDate,
                    ":created_user_id" => $userId,
                    ":updated_user_id" => $userId,
                    ":name" => $passPassword['folderName'],
                    ":description" => '',
                ])){
                    throw new Exception('No se pudo insertar el registro');
                }
                $passFolderId = (int)$this->db->lastInsertId();
            }

            $sql = "INSERT INTO pass_password (title, description, user_name, password, web_site, key_char, pass_folder_id)
                    VALUES (:title, :description, :user_name, :password, :web_site, :key_char, :pass_folder_id)";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
//                ":updated_at" => $currentDate,
//                ":created_at" => $currentDate,
//                ":created_user_id" => $userId,
//                ":updated_user_id" => $userId,

                ":title" => $passPassword['title'],
                ":description" => $passPassword['description'],
                ":user_name" => $passPassword['userName'],
                ":password" => $passPassword['password'],
                ":web_site" => $passPassword['webSite'],
                ":key_char" => $passPassword['keyChar'],
                ":pass_folder_id" => $passFolderId,
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            $lastInsertId = (int)$this->db->lastInsertId();

            $this->db->commit();
            $res->result = $lastInsertId;
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        }catch (PDOException $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage() . ' [PDO]';
        } catch (Exception $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function Update(array $passPassword, $userId) : Result {
        $res = new Result();
        try{
            $this->db->beginTransaction();
            $currentDate = date('Y-m-d H:i:s');

            $sql = "SELECT * FROM pass_folder WHERE name = :name LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":name" => $passPassword['folderName']]);
            $folderData = $stmt->fetch();

            $passFolderId = 0;
            if (($folderData['pass_folder_id'] ?? 0 ) >=1 ){
                $passFolderId = $folderData['pass_folder_id'];
            } else {
                $sql = "INSERT INTO pass_folder (updated_at, created_at, created_user_id, updated_user_id, name, description)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :name, :description)";

                $stmt = $this->db->prepare($sql);
                if(!$stmt->execute([
                    ":updated_at" => $currentDate,
                    ":created_at" => $currentDate,
                    ":created_user_id" => $userId,
                    ":updated_user_id" => $userId,
                    ":name" => $passPassword['folderName'],
                    ":description" => '',
                ])){
                    throw new Exception('No se pudo insertar el registro');
                }
                $passFolderId = (int)$this->db->lastInsertId();
            }

            $sql = "UPDATE pass_password SET  title = :title, description = :description, user_name = :user_name, 
                            password = :password, web_site = :web_site, key_char = :key_char, pass_folder_id = :pass_folder_id 
                    WHERE pass_password_id = :pass_password_id";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
//                ":updated_at" => $currentDate,
//                ":created_at" => $currentDate,
//                ":created_user_id" => $userId,
//                ":updated_user_id" => $userId,

                ":title" => $passPassword['title'],
                ":description" => $passPassword['description'],
                ":user_name" => $passPassword['userName'],
                ":password" => $passPassword['password'],
                ":web_site" => $passPassword['webSite'],
                ":key_char" => $passPassword['keyChar'],
                ":pass_folder_id" => $passFolderId,
                ":pass_password_id" => $passPassword['passPasswordId'],
            ])){
                throw new Exception('No se pudo actualizar el registro');
            }

            $this->db->commit();
            $res->result = $passPassword['passPasswordId'];
            $res->success = true;
            $res->message = 'El registro se actualizó exitosamente';
        }catch (PDOException $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage() . ' desde PDOException';
        } catch (Exception $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage() . ' desde Exception';
        }
        return $res;
    }

    public function GetAllByFolderId($passCustomerId){
        $res = new Result();
        try{
            $sql = 'SELECT * FROM pass_password where pass_folder_id = :pass_customer_id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':pass_customer_id' => $passCustomerId,
            ]);

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function actionAudit($action,$userId){
        $res = new Result();
        try{
            $this->db->beginTransaction();
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO pass_password_audit (user_refer_id, table_action, description, pass_password_id, create_at)
                    VALUES (:user_refer_id, :table_action, :description, :pass_password_id, :create_at)";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":user_refer_id" => $userId,
                ":table_action" => $action['tableAction'],
                ":description" => '',
                ":pass_password_id" => $action['passPasswordId'],
                ":create_at" => $currentDate,
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            $lastInsertId = (int)$this->db->lastInsertId();

            $sql = "UPDATE pass_password SET last_update = :last_update  WHERE pass_password_id = :pass_password_id";
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([
                ':last_update' => $currentDate,
                ':pass_password_id' => $action['passPasswordId']
            ])) {
                throw new Exception("Error al actualizar el registro");
            }

            $this->db->commit();
            $res->result = $lastInsertId;
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        }catch (PDOException $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage() . ' [PDO]';
        } catch (Exception $exception){
            $this->db->rollBack();
            $res->message = $exception->getMessage();
        }
        return $res;
    }
}
