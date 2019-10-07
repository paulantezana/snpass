<?php


class Model
{
    protected $table;
    protected $tableID;
    protected $db;

    public function __construct($table, $tableID, PDO $db)
    {
        $this->table = $table;
        $this->tableID = $tableID;
        $this->db = $db;
    }

    public function GetAll() : Result {
        $res = new Result();
        try{
            $sql = 'SELECT * FROM ' . $this->table;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $res->result = $stmt->fetchAll();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function Paginate($page = 1, $limit = 10) : Result {
        $res = new Result();
        try{
            $offset = ($page - 1) * $limit;

            // Total pages
            $totalRows = $this->db->query('SELECT COUNT(*) FROM ' . $this->table)->fetchColumn();
            $totalPages = ceil($totalRows / $limit);

            // Query by pages
            $sql = "SELECT * FROM {$this->table} LIMIT $offset, $limit";
            $stmt = $this->db->prepare($sql);

            // Execute
            $stmt->execute();
            $data = $stmt->fetchAll();

            $res->result = [
                'current' => $page,
                'pages' => $totalPages,
                'limit' => $limit,
                'data' => $data,
            ];
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetById(int $id) : Result {
        $res = new Result();
        try{
            $sql = "SELECT * FROM $this->table WHERE $this->tableID = :$this->tableID LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":$this->tableID"=>$id]);

            $res->result = $stmt->fetch();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function GetBy(string $columnName, $value) : Result {
        $res = new Result();
        try{
            $sql = "SELECT * FROM $this->table WHERE $columnName = :$columnName LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":$columnName" => $value]);

            $res->result = $stmt->fetch();
            $res->success = true;
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }

    public function DeleteById(int $id) :Result {
        $res = new Result();
        try{
            $sql = "DELETE FROM $this->table WHERE $this->tableID = :$this->tableID";
            $stmt = $this->db->prepare($sql);

            if (!$stmt->execute([
                ":$this->tableID" => $id,
            ])){
                throw new Exception("No se pudo elimiar el registro");
            }

            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        }catch (PDOException $exception){
            $res->message = $exception->getMessage() . ' desde PDOException';
        } catch (Exception $exception){
            $res->message = $exception->getMessage() . ' desde Exception';
        }
        return $res;
    }

    public function DeleteBy(string $columnName, $value) : Result {
        $res = new Result();
        try{
            $sql = "DELETE FROM {$this->table} WHERE $columnName = :$columnName";
            $stmt = $this->db->prepare($sql);

            if (!$stmt->execute([
                ":$columnName" => $value,
            ])){
                throw new Exception("No se pudo elimiar el registro");
            }

            $res->result = $value;
            $res->success = true;
            $res->message = 'El registro se eliminó exitosamente';
        }catch (PDOException $exception){
            $res->message = $exception->getMessage() . ' desde PDOException';
        } catch (Exception $exception){
            $res->message = $exception->getMessage() . ' desde Exception';
        }
        return $res;
    }

    public function UpdateById(int $id, array $data) : Result {
        $res = new Result();
        try{
            $sql = "UPDATE $this->table SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE $this->tableID = :$this->tableID";

            $execute = [];
            foreach ($data as $key => $value) {
                $execute[":$key"] = $value;
            }
            $execute[":$this->tableID"] = $id;

            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute($execute)) {
                throw new Exception("Error al actualizar el registro");
            }

            $res->result = $id;
            $res->success = true;
            $res->message = 'El registro se actualizó exitosamente';
        }catch (PDOException $exception){
            $res->message = $exception->getMessage() . ' desde PDOException';
        } catch (Exception $exception){
            $res->message = $exception->getMessage() . ' desde Exception';
        }
        return $res;
    }

    public function UpdateBy(string $columnName, $value, array $data) : Result {
        $res = new Result();
        try{
            $sql = "UPDATE $this->table SET ";
            foreach ($data as $key => $value) {
                $sql .= "$key = :$key, ";
            }
            $sql = trim(trim($sql), ',');
            $sql .= " WHERE $columnName = :$columnName";

            $execute = [];
            foreach ($data as $key => $value) {
                $execute[":$key"] = $value;
            }
            $execute[":$columnName"] = $value;

            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute($execute)) {
                throw new Exception("Error al actualizar el registro");
            }

            $res->result = $value;
            $res->success = true;
            $res->message = 'El registro se actualizó exitosamente';
        }catch (PDOException $exception){
            $res->message = $exception->getMessage() . ' desde PDOException';
        } catch (Exception $exception){
            $res->message = $exception->getMessage() . ' desde Exception';
        }
        return $res;
    }

    public function SearchBy(string $columnName, string $search) : Result {
        $res = new Result();
        try{
            $sql = "SELECT * FROM $this->table WHERE $columnName LIKE :$columnName  LIMIT 8";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ":$columnName" => '%' . $search . '%',
            ]);

            $res->success = true;
            $res->result = $stmt->fetchAll();
        } catch (Exception $exception){
            $res->message = $exception->getMessage();
        }
        return $res;
    }
}