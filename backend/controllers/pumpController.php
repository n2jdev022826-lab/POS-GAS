<?php


require_once __DIR__ . '/../../backend/models/pumpModel.php';


class pumpController{

    private $pump;

    public function __construct($pump)
    {
        $this->pump = new pumpModel($pump);
    }

    public function create()
    {
        $data = $_POST;

        if($this->pump->checkPump($data)){
             echo json_encode([
            "status" => "error",
            "message" => "Duplicated Pump"
        ]);
        return;
        }else{
            if($this->pump->create($data)){
                  echo json_encode([
            "status" => "success",
            "message" => "Pump added successfully"
        ]);
            }else{
                 echo json_encode([
                "status" => "error",
                "message" => "Failed to add pump"
            ]);
            }
        }




    }

    public function update()
    {
        $data = $_POST;

        if($this->pump->update($data)){
            echo json_encode([
            "status" => "success",
            "message" => "Pump Updated successfully"
        ]);


        }else{
             echo json_encode([
                "status" => "error",
                "message" => "Failed to update pump"
            ]); 
        }




    }

    public function delete()
    {
        $data = $_POST;

        if($this->pump->delete($data)){
             echo json_encode([
            "status" => "success",
            "message" => "Pump Deleted successfully"
        ]);
        }else{
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete pump"
            ]); 
        }

    }
















}