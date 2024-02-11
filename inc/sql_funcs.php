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