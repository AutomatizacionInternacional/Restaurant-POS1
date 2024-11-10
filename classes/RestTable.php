<?php
require_once('../config.php');
class RestTable extends DBConnection
{
    private $settings;
    public function __construct()
    {
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    public function __destruct()
    {
        parent::__destruct();
    }
    function capture_err()
    {
        if (!$this->conn->error)
            return false;
        else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
            return json_encode($resp);
            exit;
        }
    }
    public function save_table()
    {
        extract($_POST);
        $data = '';
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('tableid'))) {
                if (!empty($data)) $data .= " , ";
                $data .= " {$k} = '{$v}' ";
            }
        }
        if (empty($tableid)) {
            $qry = $this->conn->query("INSERT INTO rest_table set {$data}");
            if ($qry) {
                $id = $this->conn->insert_id;
                $this->settings->set_flashdata('success', 'User Details successfully saved.');
                if (!empty($_FILES['table_icon']['tmp_name'])) {
                    if (!is_dir(base_app . "uploads/mesas"))
                        mkdir(base_app . "uploads/mesas");
                    $ext = pathinfo($_FILES['table_icon']['name'], PATHINFO_EXTENSION);
                    $fname = "uploads/mesas/$id.png";
                    $accept = array('image/jpeg', 'image/png');
                    if (!in_array($_FILES['table_icon']['type'], $accept)) {
                        $err = "Image file type is invalid";
                    }
                    if ($_FILES['table_icon']['type'] == 'image/jpeg')
                        $uploadfile = imagecreatefromjpeg($_FILES['table_icon']['tmp_name']);
                    elseif ($_FILES['table_icon']['type'] == 'image/png')
                        $uploadfile = imagecreatefrompng($_FILES['table_icon']['tmp_name']);
                    if (!$uploadfile) {
                        $err = "Image is invalid";
                    }
                    $temp = imagescale($uploadfile, 200, 200);
                    if (is_file(base_app . $fname))
                        unlink(base_app . $fname);
                    $upload = imagepng($temp, base_app . $fname);
                    if ($upload) {
                        $this->conn->query("UPDATE rest_table SET table_icon = '{$fname}' WHERE tableid = {$id}");
                    }
                    imagedestroy($temp);
                }
                return 1;
            } else {
                return 2;
            }
        } else {
            $qry = $this->conn->query("UPDATE rest_table SET {$data} WHERE tableid = {$tableid}");
            if ($qry) {
    
                $id = $this->conn->insert_id;
                $this->settings->set_flashdata('success', 'User Details successfully saved.');
                if (!empty($_FILES['table_icon']['tmp_name'])) {
                    if (!is_dir(base_app . "uploads/mesas"))
                        mkdir(base_app . "uploads/mesas");
                    $ext = pathinfo($_FILES['table_icon']['name'], PATHINFO_EXTENSION);
                    $fname = "uploads/mesas/$tableid.png";
                    $accept = array('image/jpeg', 'image/png');
                    if (!in_array($_FILES['table_icon']['type'], $accept)) {
                        $err = "Image file type is invalid";
                    }
                    if ($_FILES['table_icon']['type'] == 'image/jpeg')
                        $uploadfile = imagecreatefromjpeg($_FILES['table_icon']['tmp_name']);
                    elseif ($_FILES['table_icon']['type'] == 'image/png')
                        $uploadfile = imagecreatefrompng($_FILES['table_icon']['tmp_name']);
                    if (!$uploadfile) {
                        $err = "Image is invalid";
                    }
                    $temp = imagescale($uploadfile, 200, 200);
                    if (is_file(base_app . $fname))
                        unlink(base_app . $fname);
                    $upload = imagepng($temp, base_app . $fname);
                    if ($upload) {
                        $this->conn->query("UPDATE rest_table SET table_icon = '{$fname}' WHERE tableid = {$tableid}");
                    }
                    imagedestroy($temp);
                }

                return 1;
            } else {
                return "UPDATE rest_table SET $data tableid id = {$id}";
            }
        }
    }

    public function delete_table()
    {
        extract($_POST);
        $qry = $this->conn->query("DELETE FROM rest_table where tableid = $id");
        if ($qry) {
            $this->settings->set_flashdata('success', 'Table Details successfully deleted.');
            return 1;
        } else {
            return false;
        }
    }
}

$RestTable = new RestTable();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
    case 'save_table':
        echo $RestTable->save_table();
        break;
    case 'delete':
        echo $RestTable->delete_table();
        break;
    default:
        // echo $sysset->index();
        break;
}
