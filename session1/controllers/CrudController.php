<?php
declare(strict_types=1);
class CrudController {
    public function __construct(private PDO $db, private array $entities) {}
    public function model(array $meta): EntityModel { return new EntityModel($this->db,$meta); }
    public function keys(array $meta, array $source): array { return array_intersect_key($source,array_flip($meta['pk'])); }
    public function values(array $meta, array $input, bool $editing=false): array {
        $data=[]; $errors=[];
        foreach($meta['fields'] as $name=>$field) { [$label,$type,$required]=$field; $value=trim((string)($input[$name] ?? ''));
            if($type==='boolean') $value=isset($input[$name]) ? 1 : 0;
            if($name==='mot_de_passe' && $editing && $value==='') continue;
            if($required && $value==='') $errors[]="$label est obligatoire.";
            if($type==='email' && $value!=='' && !filter_var($value,FILTER_VALIDATE_EMAIL)) $errors[]="L’email « $label » est invalide.";
            if(isset($field['min']) && $value!=='' && $value < $field['min']) $errors[]="$label est inférieur au minimum autorisé.";
            if(isset($field['max']) && $value!=='' && $value > $field['max']) $errors[]="$label dépasse le maximum autorisé.";
            if($name==='mot_de_passe' && $value!=='') $value=password_hash($value,PASSWORD_DEFAULT);
            $data[$name]=($value==='' && $type!=='boolean') ? null : $value;
        }
        if(isset($data['date_arrivee'],$data['date_depart']) && $data['date_arrivee'] && $data['date_depart'] && $data['date_depart'] <= $data['date_arrivee']) $errors[]='La date de départ doit être postérieure à la date d’arrivée.';
        if(isset($data['date_debut'],$data['date_fin']) && $data['date_debut'] && $data['date_fin'] && $data['date_fin'] < $data['date_debut']) $errors[]='La date de fin doit être postérieure ou égale à la date de début.';
        return [$data,$errors];
    }
    public function relationOptions(array $meta): array { $out=[]; foreach($meta['fields'] as $name=>$field) if($field[1]==='relation') { $target=$this->entities[$field['entity']]; $out[$name]=$this->model($target)->options($field['label']); } return $out; }
}
