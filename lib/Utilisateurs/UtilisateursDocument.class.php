<?php

class UtilisateursDocument implements IUtilisateursDocument
{
    protected $document;

    public function __construct(acCouchdbDocument $document)
    {
        $this->document = $document;
    }

    public function addEdition($id_user, $date) {
        $this->document->add('utilisateurs')->add('edition')->add($id_user, $date);
    }

    public function addValidation($id_user, $date) {
        $this->document->add('utilisateurs')->add('validation')->add($id_user, $date);
    }

    public function getEdition() {
        if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('edition'))) 
            return $this->document->get('utilisateur')->get('edition');
        return null;
    }

    public function getValidation() {    
        if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('validation'))) 
            return $this->document->get('utilisateur')->get('validation');
        return null;
    }
    
    public function getLastEdition() {
        $editions = $this->getEdition();
        if(!$editions) return null;
        $last_date = null;
        $editeur = null;
        foreach($editions as $key => $date) {
            if(!$last_date){
                $last_date = $date;
                $editeur = $key;
            }elseif ($date > $last_date) {                
                $last_date = $date;
                $editeur = $key;
            }
        }
        return $editeur;
    }

    public function getLastValidation() {        
        $validations = $this->getValidation();
        if(!$validations) return null;
        $last_date = null;
        $validation = null;
        foreach($validations as $key => $date) {
            if(!$last_date){
                $last_date = $date;
                $validation = $key;
            }elseif ($date > $last_date) {                
                $last_date = $date;
                $validation = $key;
            }
        }
        return $validation;
    }

    public function removeValidation() {
       if($this->document && ($this->document->exist('utilisateur')) && ($this->document->utilisateur->exist('validation'))) 
           $this->document->utilisateur->remove('validation');
    }
}
