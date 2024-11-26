<?php
    function updateRecord($connection, $table, $columnToUpdate, $newValue, $conditionColumn, $conditionValue) {
        $sql = "UPDATE $table SET $columnToUpdate = ? WHERE $conditionColumn = ?";
        
        $statement = $connection->prepare($sql);
        $statement->bind_param("ss", $newValue, $conditionValue);
        $statement->execute();
        
        if ($statement->affected_rows <= 0) {
            echo "Error of function: updateRecord";
        }
        
        $statement->close();
    }

    function deleteRecord($connection, $table, $conditionColumn, $conditionValue) {
        $sql = "DELETE FROM $table WHERE $conditionColumn = ?";
        
        $statement = $connection->prepare($sql);
        
        $statement->bind_param("s", $conditionValue);
        
        $statement->execute();
        
        if ($statement->affected_rows <= 0) {
            echo "Error of function: deleteRecord";
        }
        
        $statement->close();
    }
    
    function fetchMaxID($conn, $tableName) {
        $query = "SELECT MAX(id) AS max_id FROM $tableName";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['max_id'];
    }

    function fetchRecords($connection, $table, $conditionColumn = null, $conditionValue = null) {
        $sql = "SELECT * FROM $table";
        if ($conditionColumn !== null && $conditionValue !== null) {
            $sql .= " WHERE $conditionColumn = ?";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("s", $conditionValue);
        } else {
            $stmt = $connection->prepare($sql);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
    
        return $rows;
    }
    

    function fetchLastRecordByCondition($connection, $table, $conditionColumn = null, $conditionValue = null, $orderByColumn = 'id', $orderDirection = 'DESC', $limit = 1) {
        $sql = "SELECT * FROM $table";
        
        $params = [];
        if ($conditionColumn !== null && $conditionValue !== null) {
            $sql .= " WHERE $conditionColumn = ?";
            $params[] = $conditionValue;
        }
        
        $sql .= " ORDER BY $orderByColumn $orderDirection LIMIT $limit";
    
        $stmt = $connection->prepare($sql);
        if ($conditionColumn !== null && $conditionValue !== null) {
            $stmt->bind_param("s", ...$params);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        $stmt->close();
    
        return $row;
    }
    
    

    function addNewRecord($connection, $table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
    
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
        $statement = $connection->prepare($sql);
        $types = ""; 
        foreach ($data as $value) {
            if (is_string($value)) {
                $types .= 's'; // string
            } elseif (is_int($value)) {
                $types .= 'i'; // integer
            } elseif (is_float($value)) {
                $types .= 'd'; // double
            } else {
                $types .= 'b'; // blob i inne
            }
        }
    
        $args = array_merge([$types], array_values($data));
    
        $args_ref = [];
        foreach ($args as $key => &$arg) {
            $args_ref[$key] = &$arg;
        }
    
        call_user_func_array([$statement, 'bind_param'], $args_ref);
    
        $statement->execute();
    
        if ($statement->affected_rows <= 0) {
            echo "Error of function: addNewRecord";
        }
    
        $statement->close();
    }
    
    
?>