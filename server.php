<?php

include 'classes/CRUD.php';
include 'classes/XMLParser.php';

//If $_POST is set then start CRUD <- set database connection and XMLParser

if (isset($_POST['method'])) {

    $crud = new CRUD(new PDO("mysql:host=localhost;dbname=RSS", 'homestead', 'secret'));
    $xml_parser = new XMLParser();

    //Show All Data - Get all results and create array, else return error

    if($_POST['method'] === 'show') {
        if($crud->show()) {
            echo json_encode(array(
                'flash_warning' => 'success',
                'flash' => 'Current list.',
                'data' => $crud->show(),
            ));
        } else {
            echo json_encode(array(
                'flash_warning' => 'error',
                'flash' => 'Could not find any links.',
                'data' => $crud->show(),
        ));
        }
    }

    //Insert Data - Get all results and create array, else return error

    if($_POST['method'] === 'insert') {
        if(isset($_POST['link'])) {
            if($crud->insert($_POST['link'])) {
                echo json_encode(array(
                    'flash_warning' => 'success',
                    'flash' => 'Link inserted.',
                    'data' => $crud->show(),
                ));
            } else {
                echo json_encode(array(
                    'flash_warning' => 'error',
                    'flash' => 'Link could not be inserted, it already exists.',
                    'data' => $crud->show(),
                ));
            }
        } else {
            echo json_encode(array(
                'flash_warning' => 'error',
                'flash' => 'Link is invalid or empty.',
                'data' => $crud->show(),
            ));
        }
    }

    //Update Data - Get all results and create array, else return error

    if($_POST['method'] === 'update') {
        if(isset($_POST['oldlink']) && isset($_POST['newlink'])) {
            if($crud->update($_POST['oldlink'], $_POST['newlink'])) {
                echo json_encode(array(
                    'flash_warning' => 'success',
                    'flash' => 'Link updated.',
                    'data' => $crud->show(),
                ));
            } else {
                echo json_encode(array(
                    'flash_warning' => 'error',
                    'flash' => 'Link could not be updated.',
                    'data' => $crud->show(),
                ));
            }
        } else {
            echo json_encode(array(
                'flash_warning' => 'error',
                'flash' => 'Link is invalid or empty.',
                'data' => $crud->show(),
            ));
        }
    }

    //Delete Data - Get all results and create array, else return error

    if($_POST['method'] === 'delete') {
        if(isset($_POST['link'])) {
            if($crud->delete($_POST['link'])) {
                echo json_encode(array(
                    'flash_warning' => 'success',
                    'flash' => 'Link deleted.',
                    'data' => $crud->show(),
                ));
            } else {
                echo json_encode(array(
                    'flash_warning' => 'error',
                    'flash' => 'Link could not be deleted, it either does not exist or has already been deleted.',
                    'data' => $crud->show(),
                ));
            }
        } else {
            echo json_encode(array(
                'flash_warning' => 'error',
                'flash' => 'Link is invalid or empty.',
                'data' => $crud->show(),
            ));
        }
    }

    //XML Parse Data - Get all results and create array, else return error

    if($_POST['method'] === 'xml') {
        if(isset($_POST['link'])) {
            if($xml_parser->getParsedContent($_POST['link'])) {
                echo json_encode(array(
                    'flash_warning' => 'success',
                    'flash' => 'Here is the list for the parsed url.',
                    'data' => $crud->show(),
                    'xml_data' => $xml_parser->getParsedContent($_POST['link']),
                ));
            } else {
                echo json_encode(array(
                    'flash_warning' => 'error',
                    'flash' => 'Link invalid or does not contain xml content.',
                    'data' => $crud->show(),
                ));
            }
        } else {
            echo json_encode(array(
                'flash_warning' => 'error',
                'flash' => 'Link is invalid or empty.',
                'data' => $crud->show(),
            )); 
        }
    }
}