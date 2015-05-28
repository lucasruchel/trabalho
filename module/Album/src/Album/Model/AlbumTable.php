<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Album\Model;
use Zend\Db\TableGateway\TableGateway;
use \Zend\Db\Sql\Select;
class AlbumTable{
     protected $tg;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tg = $tableGateway;
     }
     public function fetchAll($currentPage=1,$countPerPage=6)
     {
         //$select = new \Zend\Db\Sql\Select
         
         $resultSet = $this->tg->select();
         return $resultSet;
     }
     
     public function getAlbum($id){
          $id  = (int) $id;
         $rowset = $this->tg->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }
     public function saveAlbum(Album $album){
         
          $data = array(
             'artist' => $album->artist,
             'title'  => $album->title,
             'file' => $album->file,
         );
       
        
         $id = (int) $album->id;
         if ($id == 0) {
             $this->tg->insert($data);
         } else {
             if ($this->getAlbum($id)) {
                 $this->tg->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Album id does not exist');
             }
         }
     }
     
     public function deleteAlbum($id){
          $this->tg->delete(array('id' => (int) $id));
     }
}