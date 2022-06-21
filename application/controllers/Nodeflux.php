<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nodeflux extends CI_Controller
{

    // construct
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Guzzle');
    }

    public function index()
    {
        $params = [
            'access_key' => 'C83YWDG6WLGNSZIV59322B5DC',
            'secret_key' => 'Pt6cGqawLhYzwxQsPqlpeXIyOjsTGCbQS2wKrO9tLl_MxNxnTAtXAWY_9x2LHkSf'
        ];

        $data = $this->guzzle->init($params);

        if($data['status'] == true){
            redirect(base_url());
        }else{
            ej($data);
        }
    }

    public function upload(){
        $this->templatefront->view('home/home');
    }

    public function scan(){
        $this->templatefront->view('home/scan');
    }

    public function set_face(){

        // upload scan foto
        $path = 'berkas/upload/';
        $upload = $this->uploader->uploadImage($_FILES['image'], $path);

        ej($upload);
    }

    public function check_face()
    {

        $face_scan = $this->input->post('file');

        // upload foto main
        $path = 'berkas/upload/';
        $upload = $this->uploader->uploadImage($_FILES['image'], $path);

        $type = pathinfo($upload['filename'], PATHINFO_EXTENSION);
        $data = file_get_contents($upload['filename']);
        $face_image = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $params = [
            'threshold' => 0.75,
            'face_image' => $face_image,
            'face_scan' => $face_scan,
        ];
        
        $data = $this->guzzle->face_match($params);

        // ej($data);

        $data['ok'] = true;

        $this->templatefront->view('home/home', $data);
    }
}
