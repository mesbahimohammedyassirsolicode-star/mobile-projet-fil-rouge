<?php
declare(strict_types=1);
class EntityModel {
    public function __construct(private PDO $db, private array $meta) {}
    private function where(array $keys): array { $parts=[]; foreach ($this->meta['pk'] as $key) { if (!isset($keys[$key])) throw new InvalidArgumentException('Identifiant manquant.'); $parts[]="`$key` = :$key"; } return [implode(' AND ',$parts), array_intersect_key($keys,array_flip($this->meta['pk']))]; }
    public function all(): array { return $this->db->query('SELECT * FROM `'.$this->meta['name'].'` ORDER BY `'.$this->meta['pk'][0].'` DESC')->fetchAll(); }
    public function find(array $keys): ?array { [$where,$params]=$this->where($keys); $s=$this->db->prepare('SELECT * FROM `'.$this->meta['name'].'` WHERE '.$where); $s->execute($params); return $s->fetch() ?: null; }
    public function create(array $data): void { $cols=array_keys($data); $s=$this->db->prepare('INSERT INTO `'.$this->meta['name'].'` (`'.implode('`,`',$cols).'`) VALUES (:'.implode(',:',$cols).')'); $s->execute($data); }
    public function update(array $keys,array $data): void { $sets=[]; foreach(array_keys($data) as $col) $sets[]="`$col`=:$col"; [$where,$params]=$this->where($keys); $s=$this->db->prepare('UPDATE `'.$this->meta['name'].'` SET '.implode(',',$sets).' WHERE '.$where); $s->execute(array_merge($data,$params)); }
    public function delete(array $keys): void { [$where,$params]=$this->where($keys); $s=$this->db->prepare('DELETE FROM `'.$this->meta['name'].'` WHERE '.$where); $s->execute($params); }
    public function options(string $field): array { return $this->db->query("SELECT `$field` AS id, `$field` AS label FROM `{$this->meta['name']}` ORDER BY `$field`")->fetchAll(); }
    public function count(): int { return (int)$this->db->query('SELECT COUNT(*) FROM `'.$this->meta['name'].'`')->fetchColumn(); }
}
