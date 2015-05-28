<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Album\Form\AlbumForm; //form
use Album\Model\Album;  //valida form

use Zend\Debug\Debug;
use Zend\File\Transfer\Adapter\Http;

class AlbumController extends AbstractActionController
{
    protected $AlbumTable;
    
    public function getAlbumTable()
    {
    if (!$this->AlbumTable) {
        $sm = $this->getServiceLocator();
        $this->albumTable = $sm->get('Album\Model\AlbumTable');
    }
    return $this->albumTable;
    }

    public function indexAction()
    {
        return new ViewModel(array(
             'albums' => $this->getAlbumTable()->fetchAll(),
         ));
    }

    public function addAction()
       {
           $form = new AlbumForm();
           $form->get('submit')->setValue('Add');

           $request = $this->getRequest();
           if ($request->isPost()) {
               $album = new Album();
               $form->setInputFilter($album->getInputFilter());
               
               //$form->setData($request->getPost());
               //$form->setData($request->getFiles());
               
               $post = array_merge_recursive(
                       $request->getPost()->toArray(),
                       $request->getFiles()->toArray()
                );
               
               $form->setData($post);
               
               
               
               if ($form->isValid()) {
                   $album->exchangeArray($form->getData());
                   
                   //Validação e Colocar o arquivo aonde querer
                   $adapter = new \Zend\File\Transfer\Adapter\Http();
                   
                  // $adapter->setValidator($album->file['name']);
                   
                   $adapter->addValidator('Extension',false,'jpg,jpeg,png,gif');
                   $adapter->addValidator('IsImage',true);
                   
                   
                   if($adapter->isvalid()){
                      $adapter->setDestination(getcwd().'/public/img/');
                       $adapter->receive($album->file['name']);
                       $album->file = 'img/' . $album->file['name'];

                   }else{
                       $album->file = 'img/default.png';
                   }
                   
                  
                   //Salva no banco
                   $this->getAlbumTable()->saveAlbum($album);

                   // Redirect to list of albums
                   return $this->redirect()->toRoute('album');
               }
           }
           return array('form' => $form);
       }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
           if ($id == 0) {
               return $this->redirect()->toRoute('album', array(
                   'action' => 'add'
               ));
            }
        
        try {
            $album = $this->getAlbumTable()->getAlbum($id);
            }
                catch (\Exception $ex) {
                return $this->redirect()->toRoute('album', array(
                   'action' => 'index'
               ));
           }
        
           $form  = new AlbumForm();
           $form->bind($album);
           $form->get('submit')->setAttribute('value', 'Edit');

           $request = $this->getRequest();
           if ($request->isPost()) {
               
               $form->setInputFilter($album->getInputFilter());
               
                $post = array_merge_recursive(
                       $request->getPost()->toArray(),
                       $request->getFiles()->toArray()
                );
                
                $form->setData($post);
                
               if ($form->isValid()) {
                   
                    //Validação e Colocar o arquivo aonde querer
                   $adapter = new \Zend\File\Transfer\Adapter\Http();
                   
                  // $adapter->setValidator($album->file['name']);
                  $adapter->addValidator('Extension',false,'jpg,jpeg,png,gif');
                  $adapter->addValidator('IsImage',true); 
                   
                   
                   if($adapter->isvalid()){
                       $adapter->setDestination(getcwd().'/public/img/');
                       $adapter->receive($album->file['name']);
                       $album->file = 'img/' . $album->file['name'];

                   }else{
                       $album->file = 'img/default.png';
                   }
                   
                   
                   
                   
                   
                   $this->getAlbumTable()->saveAlbum($album);

                   // Redirect to list of albums
                   return $this->redirect()->toRoute('album');
               }
           }
           
    return array(
               'id' => $id,
               'form' => $form,
           );
       }

  public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('album');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getAlbumTable()->deleteAlbum($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('album');
         }

         return array(
             'id'    => $id,
             'album' => $this->getAlbumTable()->getAlbum($id),
         );
     }
}