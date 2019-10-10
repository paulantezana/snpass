<?php


class PassFolder extends Model
{
    public function __construct(PDO $connection)
    {
        parent::__construct("pass_folder","pass_folder_id", $connection);
    }

    public function scroll($page, $search){
        $res = new Result();
        $res->current = 1;
        $res->total = 0;
        $res->more = false;

        try{
            $pageCount = 20;
            $offset = ($page - 1) * $pageCount;

            // Total pages
            $totalRows = $this->db->query("SELECT COUNT(*) FROM pass_folder WHERE name LIKE '%" . $search . "%'")->fetchColumn();
            $totalPages = ceil($totalRows / $pageCount);

            // Query by pages
            $sql = "SELECT * FROM pass_folder WHERE name LIKE :name ORDER BY pass_folder_id DESC LIMIT $offset, $pageCount";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => '%' . $search . '%'
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

    public function Insert($passCustomer, $userId){
        $res = new Result();
        try{
            $currentDate = date('Y-m-d H:i:s');

            $sql = "INSERT INTO pass_folder (updated_at, created_at, created_user_id, updated_user_id, name, description)
                    VALUES (:updated_at, :created_at, :created_user_id, :updated_user_id, :name, :description)";

            $stmt = $this->db->prepare($sql);
            if(!$stmt->execute([
                ":updated_at" => $currentDate,
                ":created_at" => $currentDate,
                ":created_user_id" => $userId,
                ":updated_user_id" => $userId,
                ":name" => $passCustomer['name'],
                ":description" => $passCustomer['description'],
            ])){
                throw new Exception('No se pudo insertar el registro');
            }
            $lastInsertId = (int)$this->db->lastInsertId();

            $res->result = $lastInsertId;
            $res->success = true;
            $res->message = 'El registro se inserto exitosamente';
        }catch (PDOException $exception){
            $res->message = $exception->getMessage() . ' [PDO]';
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }
}