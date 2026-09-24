<?php
declare(strict_types=1);
session_start();
require __DIR__.'/config/database.php'; require __DIR__.'/config/entities.php'; require __DIR__.'/models/EntityModel.php'; require __DIR__.'/controllers/CrudController.php';
$entities=entities(); $entity=$_GET['entity'] ?? 'Hotel'; if(!isset($entities[$entity])) $entity='Hotel'; $meta=$entities[$entity];
try { $controller=new CrudController(db(),$entities); $model=$controller->model($meta); $action=$_GET['action'] ?? 'list';
  if($_SERVER['REQUEST_METHOD']==='POST') { $keys=$controller->keys($meta,$_POST); if(($_POST['form_action']??'')==='delete') { $model->delete($keys); $_SESSION['success']='L’élément a été supprimé.'; } else { [$data,$errors]=$controller->values($meta,$_POST,($_POST['form_action']??'')==='edit'); if($errors) { $_SESSION['errors']=$errors; $_SESSION['old']=$_POST; header('Location: ?entity='.urlencode($entity).'&action='.($_POST['form_action']==='edit'?'edit':'create').'&'.http_build_query($keys)); exit; } if($_POST['form_action']==='edit') { $model->update($keys,$data); $_SESSION['success']='Les modifications ont été enregistrées.'; } else { $model->create($data); $_SESSION['success']='L’élément a été créé.'; } } header('Location: ?entity='.urlencode($entity)); exit; }
  $record=in_array($action,['show','edit','delete'],true)?$model->find($controller->keys($meta,$_GET)):null; if(in_array($action,['show','edit','delete'],true)&&!$record){$_SESSION['error']='Élément introuvable.';header('Location: ?entity='.urlencode($entity));exit;} $options=in_array($action,['create','edit'],true)?$controller->relationOptions($meta):[];
  $dashboard=[]; foreach($entities as $n=>$m) $dashboard[$n]=$controller->model($m)->count();
} catch(Throwable $e) { $fatal=$e->getMessage(); $action='error'; }
require __DIR__.'/views/layout.php';
